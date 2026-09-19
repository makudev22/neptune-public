<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\shape;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class PrimitiveShapeLinePayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_LINE;

	public function __construct(
		private Vector3 $lineEndLocation,
	){}

	public function getLineEndLocation() : Vector3{ return $this->lineEndLocation; }

	public static function read(NetworkBinaryStream $in) : self{
		return new self($in->getVector3());
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putVector3($this->lineEndLocation);
	}
}
