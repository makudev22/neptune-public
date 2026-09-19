<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class ChunkPosition
{
	public function __construct(
		private int $x,
		private int $z
	) {
	}

	public function getX() : int
	{
		return $this->x;
	}

	public function getZ() : int
	{
		return $this->z;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$x = $in->getVarInt();
		$z = $in->getVarInt();

		return new self($x, $z);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putVarInt($this->x);
		$out->putVarInt($this->z);
	}
}
