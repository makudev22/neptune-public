<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\shape;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class PrimitiveShapeEllipsoidPayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_ELLIPSOID;

	public function __construct(
		private Vector3 $radii,
		private int $segmentsPerAxis,
	){}

	public function getRadii() : Vector3{ return $this->radii; }

	public function getSegmentsPerAxis() : int{ return $this->segmentsPerAxis; }

	public static function read(NetworkBinaryStream $in) : self{
		return new self(
			$in->getVector3(),
			$in->getByte()
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putVector3($this->radii);
		$out->putByte($this->segmentsPerAxis);
	}
}
