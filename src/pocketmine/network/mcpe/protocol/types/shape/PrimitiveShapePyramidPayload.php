<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\shape;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class PrimitiveShapePyramidPayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_PYRAMID;

	public function __construct(
		private float $width,
		private ?float $depth,
		private float $height,
	){}

	public function getWidth() : float{ return $this->width; }

	public function getDepth() : ?float{ return $this->depth; }

	public function getHeight() : float{ return $this->height; }

	public static function read(NetworkBinaryStream $in) : self{
		return new self(
			$in->getLFloat(),
			$in->getOptional($in->getLFloat(...)),
			$in->getLFloat()
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putLFloat($this->width);
		$out->putOptional($this->depth, $out->putLFloat(...));
		$out->putLFloat($this->height);
	}
}
