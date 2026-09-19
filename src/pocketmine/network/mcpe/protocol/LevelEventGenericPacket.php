<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\NetworkSession;

class LevelEventGenericPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::LEVEL_EVENT_GENERIC_PACKET;

	public int $eventId;
	/** @var string network-format NBT */
	public string $eventData;

	public static function create(int $eventId, CompoundTag $data) : self
	{
		$result = new self();
		$result->eventId = $eventId;
		$result->eventData = (new NetworkLittleEndianNBTStream())->write($data);
		return $result;
	}

	public function getEventId() : int
	{
		return $this->eventId;
	}

	public function getEventData() : string
	{
		return $this->eventData;
	}

	protected function decodePayload() : void
	{
		$this->eventId = $this->getVarInt();
		$this->eventData = $this->getRemaining();
	}

	protected function encodePayload() : void
	{
		$this->putVarInt($this->eventId);
		($this->buffer .= $this->eventData);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleLevelEventGeneric($this);
	}
}
