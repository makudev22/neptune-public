<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe;

interface PacketSender
{
	/**
	 * Sends a DataPacket to the interface, returns an unique identifier for the packet if $needACK is true
	 */
	public function putBuffer(int $sessionId, string $payload, bool $needACK = false, bool $immediate = true) : ?int;

	/**
	 * Terminates the connection
	 */
	public function close(int $sessionId, string $reason = "unknown reason") : void;
}
