<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pocketmine\network\mcpe\NetworkBinaryStream;

abstract class RecipeWithTypeId{
	protected function __construct(
		private int $typeId
	){}

	final public function getTypeId() : int{
		return $this->typeId;
	}

	abstract public function encode(NetworkBinaryStream $out) : void;
}
