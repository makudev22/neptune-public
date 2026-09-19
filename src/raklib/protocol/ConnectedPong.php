<?php


declare(strict_types=1);

namespace raklib\protocol;

class ConnectedPong extends ConnectedPacket
{
	public static $ID = MessageIdentifiers::ID_CONNECTED_PONG;

	public int $sendPingTime;
	public int $sendPongTime;

	public static function create(int $sendPingTime, int $sendPongTime) : self
	{
		$result = new self();
		$result->sendPingTime = $sendPingTime;
		$result->sendPongTime = $sendPongTime;
		return $result;
	}

	protected function encodePayload(PacketSerializer $out) : void
	{
		$out->putLong($this->sendPingTime);
		$out->putLong($this->sendPongTime);
	}

	protected function decodePayload(PacketSerializer $in) : void
	{
		$this->sendPingTime = $in->getLong();
		$this->sendPongTime = $in->getLong();
	}
}
