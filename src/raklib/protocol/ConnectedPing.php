<?php


declare(strict_types=1);

namespace raklib\protocol;

class ConnectedPing extends ConnectedPacket
{
	public static $ID = MessageIdentifiers::ID_CONNECTED_PING;

	public int $sendPingTime;

	public static function create(int $sendPingTime) : self
	{
		$result = new self();
		$result->sendPingTime = $sendPingTime;
		return $result;
	}

	protected function encodePayload(PacketSerializer $out) : void
	{
		$out->putLong($this->sendPingTime);
	}

	protected function decodePayload(PacketSerializer $in) : void
	{
		$this->sendPingTime = $in->getLong();
	}
}
