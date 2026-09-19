<?php


declare(strict_types=1);

namespace raklib\protocol;

class DisconnectionNotification extends ConnectedPacket
{
	public static $ID = MessageIdentifiers::ID_DISCONNECTION_NOTIFICATION;

	protected function encodePayload(PacketSerializer $out) : void
	{

	}

	protected function decodePayload(PacketSerializer $in) : void
	{

	}
}
