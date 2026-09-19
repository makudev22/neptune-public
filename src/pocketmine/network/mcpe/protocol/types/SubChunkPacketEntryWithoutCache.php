<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class SubChunkPacketEntryWithoutCache
{
	public function __construct(
		private SubChunkPacketEntryCommon $base
	) {
	}

	public function getBase() : SubChunkPacketEntryCommon
	{
		return $this->base;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		return new self(SubChunkPacketEntryCommon::read($in, false));
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$this->base->write($out, false);
	}
}
