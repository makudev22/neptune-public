<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class ComplexAliasItemDescriptor implements ItemDescriptor{
	use GetTypeIdFromConstTrait;

	public const ID = ItemDescriptorType::COMPLEX_ALIAS;

	public function __construct(
		private string $alias
	){}

	public function getAlias() : string{ return $this->alias; }

	public static function read(NetworkBinaryStream $in) : self{
		$alias = $in->getString();

		return new self($alias);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->alias);
	}
}
