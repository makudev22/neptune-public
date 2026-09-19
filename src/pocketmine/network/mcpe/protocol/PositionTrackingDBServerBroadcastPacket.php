<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\NetworkSession;

class PositionTrackingDBServerBroadcastPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::POSITION_TRACKING_D_B_SERVER_BROADCAST_PACKET;

	public const int ACTION_UPDATE = 0;
	public const int ACTION_DESTROY = 1;
	public const int ACTION_NOT_FOUND = 2;

	public int $action;
	public int $trackingId;
	public CompoundTag $nbt;

	public static function create(int $action, int $trackingId, CompoundTag $nbt) : self
	{
		$result = new self();
		$result->action = $action;
		$result->trackingId = $trackingId;
		$result->nbt = $nbt;
		return $result;
	}

	public function getAction() : int
	{
		return $this->action;
	}

	public function getTrackingId() : int
	{
		return $this->trackingId;
	}

	public function getNbt() : CompoundTag
	{
		return $this->nbt;
	}

	protected function decodePayload() : void
	{
		$this->action = $this->getByte();
		$this->trackingId = $this->getVarInt();
		$offset = $this->getOffset();
		$nbt = (new NetworkLittleEndianNBTStream())->read($this->getBuffer(), false, $offset);
		$this->setOffset($offset);
		if (!($nbt instanceof CompoundTag)) {
			throw new PacketDecodeException("Expected TAG_Compound");
		}
		$this->nbt = $nbt;
	}

	protected function encodePayload() : void
	{
		$this->putByte($this->action);
		$this->putVarInt($this->trackingId);
		$this->put((new NetworkLittleEndianNBTStream())->write($this->nbt));
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handlePositionTrackingDBServerBroadcast($this);
	}
}
