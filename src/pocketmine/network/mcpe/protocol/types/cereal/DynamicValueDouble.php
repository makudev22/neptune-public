<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\cereal;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class DynamicValueDouble extends DynamicValue{
	use GetTypeIdFromConstTrait;

	public const ID = DynamicValueType::DOUBLE;

	public function __construct(
		private float $value
	){}

	protected static function readValue(NetworkBinaryStream $in) : self{
		return new self($in->getLDouble());
	}

	protected function writeValue(NetworkBinaryStream $out) : void{
		$out->putLDouble($this->value);
	}
}
