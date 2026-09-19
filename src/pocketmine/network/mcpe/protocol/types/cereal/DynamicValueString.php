<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\cereal;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class DynamicValueString extends DynamicValue{
	use GetTypeIdFromConstTrait;

	public const ID = DynamicValueType::STRING;

	public function __construct(
		private string $value
	){}

	protected static function readValue(NetworkBinaryStream $in) : self{
		return new self($in->getString());
	}

	protected function writeValue(NetworkBinaryStream $out) : void{
		$out->putString($this->value);
	}
}
