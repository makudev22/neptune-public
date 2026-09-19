<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\shape;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class PrimitiveShapeBoxPayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_BOX;

	public function __construct(
		private Vector3 $boxBound,
	){}

	public function getBoxBound() : Vector3{ return $this->boxBound; }

	public static function read(NetworkBinaryStream $in) : self{
		return new self($in->getVector3());
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putVector3($this->boxBound);
	}
}
