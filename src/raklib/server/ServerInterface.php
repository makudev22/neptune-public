<?php


declare(strict_types=1);

namespace raklib\server;

use raklib\protocol\EncapsulatedPacket;

interface ServerInterface
{
	public function sendEncapsulated(int $sessionId, EncapsulatedPacket $packet, bool $immediate = false) : void;

	public function sendRaw(string $address, int $port, string $payload) : void;

	public function closeSession(int $sessionId) : void;

	public function setName(string $name) : void;

	public function setPortCheck(bool $value) : void;

	public function setPacketsPerTickLimit(int $limit) : void;

	public function blockAddress(string $address, int $timeout) : void;

	public function unblockAddress(string $address) : void;

	public function addRawPacketFilter(string $regex) : void;
}
