<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\cereal;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class DynamicValueBool extends DynamicValue{
	use GetTypeIdFromConstTrait;

	public const ID = DynamicValueType::BOOL;

	public function __construct(
		private bool $value
	){}

	protected static function readValue(NetworkBinaryStream $in) : self{
		return new self($in->getBool());
	}

	protected function writeValue(NetworkBinaryStream $out) : void{
		$out->putBool($this->value);
	}

}
