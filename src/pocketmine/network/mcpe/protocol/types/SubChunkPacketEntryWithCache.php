<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class SubChunkPacketEntryWithCache
{
	public function __construct(
		private SubChunkPacketEntryCommon $base,
		private int $usedBlobHash
	) {
	}

	public function getBase() : SubChunkPacketEntryCommon
	{
		return $this->base;
	}

	public function getUsedBlobHash() : int
	{
		return $this->usedBlobHash;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$base = SubChunkPacketEntryCommon::read($in, true);
		$usedBlobHash = $in->getLLong();

		return new self($base, $usedBlobHash);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$this->base->write($out, true);
		$out->putLLong($this->usedBlobHash);
	}
}
