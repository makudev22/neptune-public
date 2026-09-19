<?php


declare(strict_types=1);

namespace raklib\protocol;

class ConnectionRequest extends ConnectedPacket
{
	public static $ID = MessageIdentifiers::ID_CONNECTION_REQUEST;

	public int $clientID;
	public int $sendPingTime;
	public bool $useSecurity = false;

	protected function encodePayload(PacketSerializer $out) : void
	{
		$out->putLong($this->clientID);
		$out->putLong($this->sendPingTime);
		$out->putByte($this->useSecurity ? 1 : 0);
	}

	protected function decodePayload(PacketSerializer $in) : void
	{
		$this->clientID = $in->getLong();
		$this->sendPingTime = $in->getLong();
		$this->useSecurity = $in->getByte() !== 0;
	}
}
