<?php


declare(strict_types=1);

namespace raklib\generic;

use raklib\protocol\EncapsulatedPacket;

use function microtime;

final class ReliableCacheEntry
{
	private float $timestamp;

	/**
	 * @param EncapsulatedPacket[] $packets
	 */
	public function __construct(
		private array $packets
	) {
		$this->timestamp = microtime(true);
	}

	/**
	 * @return EncapsulatedPacket[]
	 */
	public function getPackets() : array
	{
		return $this->packets;
	}

	public function getTimestamp() : float
	{
		return $this->timestamp;
	}
}
