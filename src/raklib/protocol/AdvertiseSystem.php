<?php


declare(strict_types=1);

namespace raklib\protocol;

class AdvertiseSystem extends Packet
{
	public static $ID = MessageIdentifiers::ID_ADVERTISE_SYSTEM;

	public string $serverName;

	protected function encodePayload(PacketSerializer $out) : void
	{
		$out->putString($this->serverName);
	}

	protected function decodePayload(PacketSerializer $in) : void
	{
		$this->serverName = $in->getString();
	}
}
