<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\ddui\update;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class BoolDataStoreUpdateValue extends DataStoreUpdateValue{
	use GetTypeIdFromConstTrait;

	public const ID = DataStoreUpdateValueType::BOOL;

	public function __construct(
		private readonly bool $value
	){}

	public function getValue() : bool{ return $this->value; }

	public function write(NetworkBinaryStream $out) : void{
		$out->putBool($this->value);
	}

	public static function read(NetworkBinaryStream $in) : self{
		return new self($in->getBool());
	}
}
