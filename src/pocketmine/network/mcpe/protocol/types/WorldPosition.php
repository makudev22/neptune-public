<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkBinaryStream;

final class WorldPosition{

	public function __construct(
		public Vector3 $position,
		public int $dimensionId
	){}

	public static function read(NetworkBinaryStream $in) : self{
		return new self(
			$in->getVector3(),
			$in->getVarInt()
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putVector3($this->position);
		$out->putVarInt($this->dimensionId);
	}
}
