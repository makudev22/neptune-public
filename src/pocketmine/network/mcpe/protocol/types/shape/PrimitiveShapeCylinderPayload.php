<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\shape;

use pocketmine\math\Vector2;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class PrimitiveShapeCylinderPayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_CYLINDER;

	public function __construct(
		private Vector2 $radiusX,
		private Vector2 $radiusZ,
		private float $height,
		private int $segments,
	){}

	public function getRadiusX() : Vector2{ return $this->radiusX; }

	public function getRadiusZ() : Vector2{ return $this->radiusZ; }

	public function getHeight() : float{ return $this->height; }

	public function getSegments() : int{ return $this->segments; }

	public static function read(NetworkBinaryStream $in) : self{
		return new self(
			$in->getVector2(),
			$in->getVector2(),
			$in->getLFloat(),
			$in->getByte()
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putVector2($this->radiusX);
		$out->putVector2($this->radiusZ);
		$out->putLFloat($this->height);
		$out->putByte($this->segments);
	}
}
