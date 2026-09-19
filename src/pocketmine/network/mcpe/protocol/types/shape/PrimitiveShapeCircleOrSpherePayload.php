<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\shape;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class PrimitiveShapeCircleOrSpherePayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_CIRCLE_OR_SPHERE;

	public function __construct(
		private int $segments,
	){}

	public function getSegments() : int{ return $this->segments; }

	public static function read(NetworkBinaryStream $in) : self{
		return new self($in->getByte());
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putByte($this->segments);
	}
}
