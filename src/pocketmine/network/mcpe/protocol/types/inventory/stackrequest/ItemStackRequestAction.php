<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pocketmine\network\mcpe\NetworkBinaryStream;

abstract class ItemStackRequestAction
{
	abstract public function getTypeId() : int;

	abstract public function write(NetworkBinaryStream $out) : void;
}
