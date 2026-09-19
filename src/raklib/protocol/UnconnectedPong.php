<?php


declare(strict_types=1);

namespace raklib\protocol;

class UnconnectedPong extends OfflineMessage
{
	public static $ID = MessageIdentifiers::ID_UNCONNECTED_PONG;

	public int $sendPingTime;
	public int $serverId;
	public string $serverName;

	public static function create(int $sendPingTime, int $serverId, string $serverName) : self
	{
		$result = new self();
		$result->sendPingTime = $sendPingTime;
		$result->serverId = $serverId;
		$result->serverName = $serverName;
		return $result;
	}

	protected function encodePayload(PacketSerializer $out) : void
	{
		$out->putLong($this->sendPingTime);
		$out->putLong($this->serverId);
		$this->writeMagic($out);
		$out->putString($this->serverName);
	}

	protected function decodePayload(PacketSerializer $in) : void
	{
		$this->sendPingTime = $in->getLong();
		$this->serverId = $in->getLong();
		$this->readMagic($in);
		$this->serverName = $in->getString();
	}
}
