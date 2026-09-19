<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\ddui\update;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class StringDataStoreUpdateValue extends DataStoreUpdateValue{
	use GetTypeIdFromConstTrait;

	public const ID = DataStoreUpdateValueType::STRING;

	public function __construct(
		private readonly string $value
	){}

	public function getValue() : string{ return $this->value; }

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->value);
	}

	public static function read(NetworkBinaryStream $in) : self{
		return new self($in->getString());
	}
}
