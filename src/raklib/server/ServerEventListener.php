<?php


declare(strict_types=1);

namespace raklib\server;

use raklib\generic\DisconnectReason;

interface ServerEventListener
{
	public function onClientConnect(int $sessionId, string $address, int $port, int $clientID, int $protocolVersion) : void;

	/**
	 * @param int $reason one of the DisconnectReason constants
	 * @phpstan-param DisconnectReason::* $reason
	 *
	 * @see DisconnectReason
	 */
	public function onClientDisconnect(int $sessionId, int $reason) : void;

	public function onPacketReceive(int $sessionId, string $packet) : void;

	public function onRawPacketReceive(string $address, int $port, string $payload) : void;

	public function onPacketAck(int $sessionId, int $identifierACK) : void;

	public function onBandwidthStatsUpdate(int $bytesSentDiff, int $bytesReceivedDiff) : void;

	public function onPingMeasure(int $sessionId, int $pingMS) : void;
}
