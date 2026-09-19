<?php


declare(strict_types=1);

namespace raklib\protocol;

class UnconnectedPingOpenConnections extends UnconnectedPing
{
	public static $ID = MessageIdentifiers::ID_UNCONNECTED_PING_OPEN_CONNECTIONS;
}
