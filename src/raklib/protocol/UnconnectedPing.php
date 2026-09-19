<?php


declare(strict_types=1);

namespace raklib\protocol;

class UnconnectedPing extends OfflineMessage
{
	public static $ID = MessageIdentifiers::ID_UNCONNECTED_PING;

	public int $sendPingTime;
	public int $clientId;

	protected function encodePayload(PacketSerializer $out) : void
	{
		$out->putLong($this->sendPingTime);
		$this->writeMagic($out);
		$out->putLong($this->clientId);
	}

	protected function decodePayload(PacketSerializer $in) : void
	{
		$this->sendPingTime = $in->getLong();
		$this->readMagic($in);
		$this->clientId = $in->getLong();
	}
}
