<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\utils\AssumptionFailedError;

use function base64_decode;
use function file_get_contents;
use function json_decode;

use const pocketmine\BEDROCK_DATA_PATH;

class JigsawStructureDataPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::JIGSAW_STRUCTURE_DATA_PACKET;

	private CompoundTag $nbt;
	private static ?self $cachedPacket = null;

	public static function fromJson() : self
	{
		if (self::$cachedPacket !== null) {
			return self::$cachedPacket;
		}
		$data = json_decode(file_get_contents(BEDROCK_DATA_PATH . 'jigsaw_structures.json'), true);
		if (!is_array($data) || !is_string($data['nbtB64'] ?? null)) {
			throw new AssumptionFailedError('Invalid jigsaw structure data');
		}
		$nbt = (new NetworkLittleEndianNBTStream())->read(base64_decode($data['nbtB64'], true));
		if (!$nbt instanceof CompoundTag) {
			throw new AssumptionFailedError('Invalid jigsaw structure NBT');
		}
		return self::$cachedPacket = self::create($nbt);
	}

	/**
	 * @generate-create-func
	 */
	public static function create(CompoundTag $nbt) : self
	{
		$result = new self();
		$result->nbt = $nbt;
		return $result;
	}

	public function getNbt() : CompoundTag
	{
		return $this->nbt;
	}

	protected function decodePayload() : void
	{
		$this->nbt = $this->getNbtCompoundRoot();
	}

	protected function encodePayload() : void
	{
		$this->put((new NetworkLittleEndianNBTStream())->write($this->nbt));
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleJigsawStructureData($this);
	}
}
