<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class PositionTrackingDBClientRequestPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::POSITION_TRACKING_D_B_CLIENT_REQUEST_PACKET;

	public const int ACTION_QUERY = 0;

	private int $action;
	private int $trackingId;

	public static function create(int $action, int $trackingId) : self
	{
		$result = new self();
		$result->action = $action;
		$result->trackingId = $trackingId;
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

	protected function decodePayload() : void
	{
		$this->action = $this->getByte();
		$this->trackingId = $this->getVarInt();
	}

	protected function encodePayload() : void
	{
		$this->putByte($this->action);
		$this->putVarInt($this->trackingId);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handlePositionTrackingDBClientRequest($this);
	}
}
