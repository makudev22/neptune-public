<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pocketmine\network\mcpe\NetworkBinaryStream;

/**
 * Describes what items are accepted in a recipe input.
 */
interface ItemDescriptor{
	public function getTypeId() : int;

	public function write(NetworkBinaryStream $out) : void;
}
