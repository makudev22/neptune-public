<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\shape;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class PrimitiveShapeArrowPayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_ARROW;

	public function __construct(
		private ?Vector3 $lineEndLocation,
		private ?float $arrowHeadLength,
		private ?float $arrowHeadRadius,
		private ?int $segments,
	){}

	public function getLineEndLocation() : ?Vector3{ return $this->lineEndLocation; }

	public function getArrowHeadLength() : ?float{ return $this->arrowHeadLength; }

	public function getArrowHeadRadius() : ?float{ return $this->arrowHeadRadius; }

	public function getSegments() : ?int{ return $this->segments; }

	public static function read(NetworkBinaryStream $in) : self{
		return new self(
			$in->getOptional($in->getVector3(...)),
			$in->getOptional($in->getLFloat(...)),
			$in->getOptional($in->getLFloat(...)),
			$in->getOptional($in->getByte(...)),
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putOptional($this->lineEndLocation, $out->putVector3(...));
		$out->putOptional($this->arrowHeadLength, $out->putLFloat(...));
		$out->putOptional($this->arrowHeadRadius, $out->putLFloat(...));
		$out->putOptional($this->segments, $out->putByte(...));
	}
}
