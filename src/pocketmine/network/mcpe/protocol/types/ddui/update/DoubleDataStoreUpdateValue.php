<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\ddui\update;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class DoubleDataStoreUpdateValue extends DataStoreUpdateValue{
	use GetTypeIdFromConstTrait;

	public const ID = DataStoreUpdateValueType::DOUBLE;

	public function __construct(
		private readonly float $value
	){}

	public function getValue() : float{ return $this->value; }

	public function write(NetworkBinaryStream $out) : void{
		$out->putLDouble($this->value);
	}

	public static function read(NetworkBinaryStream $in) : self{
		return new self($in->getLDouble());
	}
}
