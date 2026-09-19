<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\cereal;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class DynamicValueLong extends DynamicValue{
	use GetTypeIdFromConstTrait;

	public const ID = DynamicValueType::LONG;

	public function __construct(
		private int $value
	){}

	protected static function readValue(NetworkBinaryStream $in) : self{
		return new self($in->getLLong());
	}

	protected function writeValue(NetworkBinaryStream $out) : void{
		$out->putLLong($this->value);
	}
}
