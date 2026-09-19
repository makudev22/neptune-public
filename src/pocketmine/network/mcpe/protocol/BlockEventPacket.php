<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class BlockEventPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::BLOCK_EVENT_PACKET;

	public const TYPE_CHEST = 1;

	public const DATA_CHEST_CLOSED = 0;
	public const DATA_CHEST_OPEN = 1;

	public int $x = 0;
	public int $y = 0;
	public int $z = 0;
	public int $eventType;
	public int $eventData;

	public static function create(int $x, int $y, int $z, int $eventType, int $eventData) : self
	{
		$result = new self();
		$result->x = $x;
		$result->y = $y;
		$result->z = $z;
		$result->eventType = $eventType;
		$result->eventData = $eventData;
		return $result;
	}

	protected function decodePayload() : void
	{
		$this->getBlockPosition($this->x, $this->y, $this->z);
		$this->eventType = $this->getVarInt();
		$this->eventData = $this->getVarInt();
	}

	protected function encodePayload() : void
	{
		$this->putBlockPosition($this->x, $this->y, $this->z);
		$this->putVarInt($this->eventType);
		$this->putVarInt($this->eventData);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleBlockEvent($this);
	}
}
