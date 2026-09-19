<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe;

use pocketmine\block\BlockFactory;
use pocketmine\level\format\Chunk;
use pocketmine\level\format\io\FastChunkSerializer;
use pocketmine\level\Level;
use pocketmine\network\mcpe\compression\NetworkCompression;
use pocketmine\network\mcpe\convert\block\BlockProtocolConvertor;
use pocketmine\network\mcpe\convert\block\RuntimeBlockMapping;
use pocketmine\network\mcpe\protocol\LevelChunkPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\serializer\PacketBatch;
use pocketmine\network\mcpe\protocol\types\ChunkPosition;
use pocketmine\network\mcpe\serializer\ChunkSerializer;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\thread\NonThreadSafeValue;
use pocketmine\tile\Spawnable;
use pocketmine\utils\BinaryStream;

class ChunkRequestTask extends AsyncTask
{
	protected int $levelId;
	protected string $chunk;
	protected int $chunkX;
	protected int $chunkZ;
	protected int $dimensionId;
	protected string $tiles;
	protected int $compressionLevel;
	protected int $protocol;
	/** @phpstan-var NonThreadSafeValue<BlockProtocolConvertor> */
	protected NonThreadSafeValue $blockProtocolConvertor;

	public function __construct(Level $level, int $dimensionId, Chunk $chunk, int $protocol)
	{
		$this->levelId = $level->getId();
		$this->compressionLevel = $level->getServer()->networkCompressionLevel;

		$this->chunkX = $chunk->getX();
		$this->chunkZ = $chunk->getZ();
		$this->dimensionId = $dimensionId;
		$this->chunk = FastChunkSerializer::serializeTerrain($chunk);

		$tiles = "";
		foreach ($chunk->getTiles() as $tile) {
			if ($tile instanceof Spawnable) {
				$tiles .= $tile->getSerializedSpawnCompound($protocol);
			}
		}
		$this->tiles = $tiles;

		$this->protocol = $protocol;
		$this->blockProtocolConvertor = new NonThreadSafeValue(BlockProtocolConvertor::getInstance());
	}

	public function onRun() : void
	{
		BlockFactory::init();

		$chunk = FastChunkSerializer::deserializeTerrain($this->chunk);
		$dimensionId = $this->dimensionId;

		$protocol = $this->protocol;

		$blockProtocolConvertor = $this->blockProtocolConvertor->deserialize();
		$cacheFullBlocks = [];
		if ($protocol >= ProtocolInfo::PROTOCOL_407) {
			$runtimeBlockMapping = RuntimeBlockMapping::getInstance($protocol);
			$blockLegacyToRuntime = function (int $fullId) use ($protocol, $blockProtocolConvertor, $runtimeBlockMapping, &$cacheFullBlocks) : int {
				if (!isset($cacheFullBlocks[$fullId])) {
					$block = BlockFactory::fromFullBlock($fullId);
					$blockProtocol = $blockProtocolConvertor->get($block, $protocol) ?? $block;
					$cacheFullBlocks[$fullId] = $blockProtocol->getFullId();
				}

				return $runtimeBlockMapping->toRuntimeId($cacheFullBlocks[$fullId]);
			};
		} else {
			$blockLegacyToRuntime = function (int $fullId) use ($protocol, $blockProtocolConvertor, &$cacheFullBlocks) : int {
				if (!isset($cacheFullBlocks[$fullId])) {
					$block = BlockFactory::fromFullBlock($fullId);
					$blockProtocol = $blockProtocolConvertor->get($block, $protocol) ?? $block;
					$cacheFullBlocks[$fullId] = $blockProtocol->getFullId();
				}

				return $cacheFullBlocks[$fullId];
			};
		}

		unset($cacheFullBlocks);

		$biomeLegacyToRuntime = function (int $biomeId) : int {
			return $biomeId; //TODO: old version
		};

		$pk = LevelChunkPacket::create(
			new ChunkPosition($this->chunkX, $this->chunkZ),
			$dimensionId,
			ChunkSerializer::getSubChunkCount($chunk, $dimensionId, $protocol),
			false,
			null,
			ChunkSerializer::serializeFullChunk($chunk, $blockLegacyToRuntime, $biomeLegacyToRuntime, $dimensionId, $protocol) . $this->tiles
		);
		$pk->setProtocol($protocol);

		$stream = new BinaryStream();
		PacketBatch::encodePackets($stream, [$pk], $protocol);

		$this->setResult(NetworkCompression::compress($stream->getBuffer(), $protocol, $this->compressionLevel));
	}

	public function onCompletion(Server $server) : void
	{
		$level = $server->getLevel($this->levelId);
		if ($level instanceof Level) {
			if ($this->hasResult()) {
				$level->chunkRequestCallback($this->chunkX, $this->chunkZ, $this->protocol, $this->getResult());
			} else {
				$server->getLogger()->error("Chunk request (protocol: {$this->protocol}) for world #" . $this->levelId . ", x=" . $this->chunkX . ", z=" . $this->chunkZ . " doesn't have any result data");
			}
		} else {
			$server->getLogger()->debug("Dropped chunk task due to world not loaded");
		}
	}
}
