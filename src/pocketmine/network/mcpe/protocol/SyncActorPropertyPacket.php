<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\entity\PlayerActorProperties;

use function base64_decode;
use function file_get_contents;
use function is_array;
use function json_decode;

class SyncActorPropertyPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SYNC_ACTOR_PROPERTY_PACKET;

	private CompoundTag $data;
	private static ?array $cachedPackets = null;

	public static function create(CompoundTag $data) : self
	{
		$result = new self();
		$result->data = $data;
		return $result;
	}

	public static function createFromProperties(PlayerActorProperties $properties) : self
	{
		return self::create($properties->toNbt());
	}

	public static function fromJson() : array
	{
		if (self::$cachedPackets !== null) {
			return self::$cachedPackets;
		}

		$content = file_get_contents(\pocketmine\RESOURCE_PATH . "vanilla/entity_properties.json");
		$jsonData = $content === false ? null : json_decode($content, true);
		if (!is_array($jsonData) || !isset($jsonData["entries"]) || !is_array($jsonData["entries"])) {
			return self::$cachedPackets = [];
		}

		$nbtStream = new NetworkLittleEndianNBTStream();
		$packets = [];
		foreach ($jsonData["entries"] as $entry) {
			if (!isset($entry["nbtB64"])) {
				continue;
			}
			$rawNbt = base64_decode($entry["nbtB64"], true);
			if ($rawNbt === false) {
				continue;
			}
			$compound = $nbtStream->read($rawNbt);
			if ($compound instanceof CompoundTag) {
				$packets[] = self::create($compound);
			}
		}

		return self::$cachedPackets = $packets;
	}

	public function getData() : CompoundTag
	{
		return $this->data;
	}

	protected function decodePayload() : void
	{
		$this->data = $this->getNbtCompoundRoot();
	}

	protected function encodePayload() : void
	{
		$this->put((new NetworkLittleEndianNBTStream())->write($this->data));
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSyncActorProperty($this);
	}
}
