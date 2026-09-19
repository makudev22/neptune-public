<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class AttributeLayerSettings{

	public function __construct(
		public int $priority,
		public int $weightType,
		public ?float $floatWeight,
		public ?string $stringWeight,
		public bool $enabled,
		public bool $transitionsPaused
	){}

	public static function read(NetworkBinaryStream $in) : self{
		$priority = $in->getLInt();
		$weightType = $in->getUnsignedVarInt();

		return match($weightType){
			AttributeLayerWeightType::FLOAT => new self(
				$priority,
				$weightType,
				$in->getLFloat(),
				null,
				$in->getBool(),
				$in->getBool()
			),
			AttributeLayerWeightType::STRING => new self(
				$priority,
				$weightType,
				null,
				$in->getString(),
				$in->getBool(),
				$in->getBool()
			),
			default => throw new \UnexpectedValueException("Unknown attribute layer weight type $weightType")
		};
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putLInt($this->priority);
		$out->putUnsignedVarInt($this->weightType);
		match($this->weightType){
			AttributeLayerWeightType::FLOAT => $out->putLFloat($this->floatWeight ?? 0.0),
			AttributeLayerWeightType::STRING => $out->putString($this->stringWeight ?? ""),
			default => throw new \UnexpectedValueException("Unknown attribute layer weight type $this->weightType")
		};
		$out->putBool($this->enabled);
		$out->putBool($this->transitionsPaused);
	}
}
