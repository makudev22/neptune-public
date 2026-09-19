<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\shape;

use pocketmine\math\Vector2;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class PrimitiveShapeConePayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_CONE;

	public function __construct(
		private Vector2 $radii,
		private float $height,
		private int $segments,
	){}

	public function getRadii() : Vector2{ return $this->radii; }

	public function getHeight() : float{ return $this->height; }

	public function getSegments() : int{ return $this->segments; }

	public static function read(NetworkBinaryStream $in) : self{
		return new self(
			$in->getVector2(),
			$in->getLFloat(),
			$in->getByte()
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putVector2($this->radii);
		$out->putLFloat($this->height);
		$out->putByte($this->segments);
	}
}
