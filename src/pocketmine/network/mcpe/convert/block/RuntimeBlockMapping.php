<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\convert\block;

use pocketmine\block\Block;
use pocketmine\block\BlockIds;
use pocketmine\block\Stair;
use pocketmine\block\Thin;
use pocketmine\math\Facing;
use pocketmine\nbt\LittleEndianNBTStream;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\NamedTag;
use pocketmine\network\mcpe\convert\ProtocolConvertor;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\utils\AssumptionFailedError;
use function file_get_contents;
use function getmypid;
use function json_decode;
use function ksort;
use function mt_rand;
use function mt_srand;
use function shuffle;
use const pocketmine\BEDROCK_DATA_PATH;

/**
 * @internal
 */
final class RuntimeBlockMapping
{

	/** @var int[] */
	private array $legacyToRuntimeMap = [];
	/** @var int[] */
	private array $runtimeToLegacyMap = [];

	/** @var string[] */
	private array $runtimeToNameMap = [];

	/** @var NamedTag[][] */
	private array $runtimeToStatesMap = [];

	/** @var int[] */
	private array $runtimeToVersionMap = [];

	/** @var int[] */
	private array $nbtBlockToRuntimeMap = [];
	/** @var int[] */
	private array $staticRuntimeIdToNetworkId = [];

	/** @var CompoundTag[]|array[]|null */
	private ?array $bedrockKnownStates = null;
	private ?string $bedrockEncodeBedrockKnownStates = null;

	/** @var self[] */
	private static array $instance = [];

	public static function getInstance(int $protocolVersion) : self
	{
		$protocolVersion = ProtocolConvertor::getInstance()->getBlockPaletteProtocol($protocolVersion);
		if (!isset(self::$instance[$protocolVersion])) {
			self::$instance[$protocolVersion] = new self($protocolVersion);
		}
		return self::$instance[$protocolVersion];
	}

	/**
	 * Randomizes the order of the runtimeID table to prevent plugins relying on them.
	 * Plugins shouldn't use this stuff anyway, but plugin devs have an irritating habit of ignoring what they
	 * aren't supposed to do, so we have to deliberately break it to make them stop.
	 */
	private static function randomizeTable(array $table) : array
	{
		$postSeed = mt_rand(); //save a seed to set afterwards, to avoid poor quality randoms
		mt_srand(getmypid()); //Use a seed which is the same on all threads. This isn't a secure seed, but we don't care.
		shuffle($table);
		mt_srand($postSeed); //restore a good quality seed that isn't dependent on PID
		return $table;
	}

	private function __construct(
		private readonly int $protocolVersion
	){
		if ($protocolVersion >= ProtocolInfo::PROTOCOL_2193) {
			$requiredBlockListFile = file_get_contents(BEDROCK_DATA_PATH . "block/" . $protocolVersion . "/canonical_block_states.nbt");
			if ($requiredBlockListFile === false) {
				throw new AssumptionFailedError("Missing required resource file");
			}
			$stream = new NetworkBinaryStream($requiredBlockListFile);
			$list = [];
			while (!$stream->feof()) {
				$list[] = $stream->getNbtCompoundRoot();
			}
			$this->bedrockKnownStates = $list;
			$networkIdsRaw = file_get_contents(BEDROCK_DATA_PATH . "block/" . $protocolVersion . "/block_network_ids.json");
			$networkIds = $networkIdsRaw === false ? null : json_decode($networkIdsRaw, true);
			if (!is_array($networkIds) || count($networkIds) !== count($list)) {
				throw new AssumptionFailedError("Missing or invalid block network ID map");
			}
			foreach ($networkIds as $runtimeId => $networkId) {
				$this->staticRuntimeIdToNetworkId[$runtimeId] = $networkId;
			}
			$previousMapping = self::getInstance(ProtocolInfo::PROTOCOL_1001);
			foreach ($list as $runtimeId => $state) {
				$this->addModernBlockStates($state);
				$this->staticRuntimeIdToNetworkId[$runtimeId] = self::networkBlockId($state);
				$legacyState = new CompoundTag();
				$legacyState->setString("name", $state->getString("name"));
				$legacyStates = $state->getCompoundTag("states")?->getValue() ?? [];
				unset(
					$legacyStates["minecraft:corner"],
					$legacyStates["minecraft:connection_north"],
					$legacyStates["minecraft:connection_east"],
					$legacyStates["minecraft:connection_south"],
					$legacyStates["minecraft:connection_west"]
				);
				$legacyState->setTag(new CompoundTag("states", $legacyStates));
				$previousRuntimeId = $previousMapping->fromNbtBlock($legacyState);
				if ($previousRuntimeId === 0 && $state->getString("name") !== "minecraft:air") {
					continue;
				}
				$fullId = $previousMapping->fromRuntimeId($previousRuntimeId);
				$this->registerMapping(
					$this->staticRuntimeIdToNetworkId[$runtimeId],
					$state->getString("name"),
					$state->getCompoundTag("states") ?? new CompoundTag("states"),
					$state->getInt("version"),
					$fullId >> Block::INTERNAL_METADATA_BITS,
					$fullId & ((1 << Block::INTERNAL_METADATA_BITS) - 1)
				);
			}
			return;
		}

		$requiredBlockListFile = file_get_contents(BEDROCK_DATA_PATH . "block/" . $protocolVersion . "/required_block_states.nbt");
		if ($requiredBlockListFile === false) {
			throw new AssumptionFailedError("Missing required resource file");
		}
		$stream = new NetworkBinaryStream($requiredBlockListFile);
		$list = [];
		/** @var CompoundTag[] $list */
		while (!$stream->feof()) {
			$list[] = $stream->getNbtCompoundRoot();
		}

		if ($protocolVersion >= ProtocolInfo::PROTOCOL_419) {
			$this->bedrockKnownStates = $list;
			foreach ($list as $state) {
				self::registerMapping(
					$state->getInt("runtime_id"),
					$state->getString("name"),
					$state->getCompoundTag("states"),
					$state->getInt("version"),
					$state->getInt("legacy_id"),
					$state->getShort("data")
				);
			}
		} else {
			$list = self::randomizeTable($list);
			$this->bedrockKnownStates = $list;
			foreach ($list as $runtimeId => $tag) {
				/** @var CompoundTag $tag */
				$block = $tag->getCompoundTag("block");
				$name = $block->getString("name");
				$states = $block->getCompoundTag("states");
				$version = $block->getInt("version");
				$legacyStates = $tag->getListTag("LegacyStates")->getValue();
				foreach ($legacyStates as $legacyState) {
					/** @var CompoundTag $legacyState */
					$id = $legacyState->getInt("id");
					$meta = $legacyState->getShort("val");

					self::registerMapping($runtimeId, $name, $states, $version, $id, $meta);
				}
			}
		}
	}

	private function addModernBlockStates(CompoundTag $state) : void
	{
		$name = $state->getString("name");
		$states = $state->getCompoundTag("states");
		if ($states === null) {
			$states = new CompoundTag("states");
			$state->setTag($states);
		}

		if (str_ends_with($name, "_stairs")) {
			$states->setString("minecraft:corner", "none");
		}

		if ($name === "minecraft:glass_pane" || str_ends_with($name, "_glass_pane") || str_ends_with($name, "_bars") || str_ends_with($name, "_fence") || $name === "minecraft:trip_wire") {
			$states->setByte("minecraft:connection_north", 0);
			$states->setByte("minecraft:connection_east", 0);
			$states->setByte("minecraft:connection_south", 0);
			$states->setByte("minecraft:connection_west", 0);
		}
	}

	private static function networkBlockId(CompoundTag $state) : int
	{
		$states = $state->getCompoundTag("states")?->getValue() ?? [];
		ksort($states);
		$canonical = new CompoundTag();
		$canonical->setString("name", $state->getString("name"));
		$canonical->setTag(new CompoundTag("states", $states));
		$hash = (int) hexdec(hash("fnv1a32", (new LittleEndianNBTStream())->write($canonical)));
		return $hash >= 0x80000000 ? $hash - 0x100000000 : $hash;
	}

	private function loadEncodeBedrockKnownStates() : string
	{
		return (new NetworkLittleEndianNBTStream())->write(new ListTag("", $this->bedrockKnownStates));
	}

	public function toRuntimeId(int $internalStateId) : int
	{
		return
			$this->legacyToRuntimeMap[$internalStateId] ??
			$this->legacyToRuntimeMap[BlockIds::INFO_UPDATE << Block::INTERNAL_METADATA_BITS] ??
			0;
	}

	public function toRuntimeIdForBlock(Block $block) : int
	{
		$runtimeId = $this->toRuntimeId($block->getFullId());
		if ($this->protocolVersion < ProtocolInfo::PROTOCOL_2193 || !$block->isValid()) {
			return $runtimeId;
		}

		$states = [];
		foreach ($this->toStates($runtimeId) as $key => $tag) {
			$states[$key] = clone $tag;
		}
		$name = $this->toName($runtimeId);
		$stateProperties = new CompoundTag("states", $states);
		if ($block instanceof Thin && isset($states["minecraft:connection_north"])) {
			$stateProperties->setByte("minecraft:connection_north", $block->canConnect($block->getSide(Facing::NORTH)) ? 1 : 0);
			$stateProperties->setByte("minecraft:connection_east", $block->canConnect($block->getSide(Facing::EAST)) ? 1 : 0);
			$stateProperties->setByte("minecraft:connection_south", $block->canConnect($block->getSide(Facing::SOUTH)) ? 1 : 0);
			$stateProperties->setByte("minecraft:connection_west", $block->canConnect($block->getSide(Facing::WEST)) ? 1 : 0);
		}
		if ($block instanceof Stair && isset($states["minecraft:corner"])) {
			$stateProperties->setString("minecraft:corner", $this->getStairCorner($block));
		}

		$state = new CompoundTag();
		$state->setString("name", $name);
		$state->setTag($stateProperties);
		return self::networkBlockId($state);
	}

	private function getStairCorner(Stair $stair) : string
	{
		$facing = match ($stair->getDamage() & 0x03) {
			0 => Facing::EAST,
			1 => Facing::WEST,
			2 => Facing::SOUTH,
			3 => Facing::NORTH
		};
		$upsideDown = $stair->getDamage() & 0x04;
		$front = $stair->getSide($facing);
		if ($front instanceof Stair && ($front->getDamage() & 0x04) === $upsideDown) {
			$frontFacing = $this->getStairFacing($front);
			if (Facing::axis($frontFacing) !== Facing::axis($facing)) {
				return $frontFacing === Facing::rotateY($facing, false) ? "outer_left" : "outer_right";
			}
		}
		$back = $stair->getSide(Facing::opposite($facing));
		if ($back instanceof Stair && ($back->getDamage() & 0x04) === $upsideDown) {
			$backFacing = $this->getStairFacing($back);
			if (Facing::axis($backFacing) !== Facing::axis($facing)) {
				return $backFacing === Facing::rotateY($facing, false) ? "inner_left" : "inner_right";
			}
		}
		return "none";
	}

	private function getStairFacing(Stair $stair) : int
	{
		return match ($stair->getDamage() & 0x03) {
			0 => Facing::EAST,
			1 => Facing::WEST,
			2 => Facing::SOUTH,
			3 => Facing::NORTH
		};
	}

	public function fromRuntimeId(int $runtimeId) : int{
		return $this->runtimeToLegacyMap[$runtimeId] ?? 0;
	}

	public function toName(int $runtimeId) : string {
		return $this->runtimeToNameMap[$runtimeId] ?? "minecraft:unknown";
	}

	public function toStates(int $internalStateId) : array {
		return $this->runtimeToStatesMap[$internalStateId] ?? [];
	}

	public function toVersion(int $internalStateId) : int {
		return $this->runtimeToVersionMap[$internalStateId] ?? 0;
	}

	public function toNbtBlock(int $internalStateId, bool $useVersion = false) : CompoundTag {
		$nbt = new CompoundTag();
		$nbt->setString("name", $this->toName($internalStateId));
		if ($useVersion) {
			$nbt->setInt("version", $this->toVersion($internalStateId));
		}

		$nbt->setTag(new CompoundTag("states", $this->toStates($internalStateId)));
		return $nbt;
	}

	/**
	 * @param string[] $notDeleteTags
	 */
	public function fromNbtBlock(CompoundTag $nbt, array $notDeleteTags = []) : int {
		foreach ($nbt->getValue() as $tag) {
			$name = $tag->getName();
			if ($name !== "name" && $name !== "states") {
				foreach ($notDeleteTags as $notDeleteTag) {
					if ($name === $notDeleteTag) {
						continue 2;
					}
				}

				$nbt->removeTag($name);
			}
		}

		$states = $nbt->getCompoundTag("states")->getValue();
		ksort($states);
		$nbt->setTag(new CompoundTag("states", $states));

		return $this->nbtBlockToRuntimeMap[$nbt->toString()] ?? 0;
	}

	private function registerMapping(int $staticRuntimeId, string $name, CompoundTag $states, int $version, int $legacyId, int $legacyMeta) : void
	{
		if ($legacyMeta >= (1 << Block::INTERNAL_METADATA_BITS)) {
			return;
		}

		$this->legacyToRuntimeMap[($legacyId << Block::INTERNAL_METADATA_BITS) | $legacyMeta] = $staticRuntimeId;
		$this->runtimeToLegacyMap[$staticRuntimeId] = ($legacyId << Block::INTERNAL_METADATA_BITS) | $legacyMeta;

		$this->runtimeToNameMap[$staticRuntimeId] = $name;

		$sortStates = $states->getValue();
		ksort($sortStates);
		$this->runtimeToStatesMap[$staticRuntimeId] = $sortStates;

		$this->runtimeToVersionMap[$staticRuntimeId] = $version;

		$this->nbtBlockToRuntimeMap[$this->toNbtBlock($staticRuntimeId)->toString()] = $staticRuntimeId;
	}

	/**
	 * WARNING: This method may load the palette from disk, which is a slow operation.
	 * Afterwards, it will cache the palette in memory, which requires (in some cases) tens of MB of memory.
	 * Avoid using this where possible.
	 *
	 * @return CompoundTag[]|array[]
	 */
	public function getBedrockKnownStates() : array
	{
		return $this->bedrockKnownStates;
	}

	public function getEncodeBedrockKnownStates() : string
	{
		return $this->bedrockEncodeBedrockKnownStates ??= $this->loadEncodeBedrockKnownStates();
	}

	public function getProtocolVersion() : int
	{
		return $this->protocolVersion;
	}
}
