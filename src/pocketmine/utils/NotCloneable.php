<?php


declare(strict_types=1);

namespace pocketmine\utils;

use LogicException;

trait NotCloneable
{
	final public function __clone()
	{
		throw new LogicException("Cloning " . static::class . " objects is not allowed");
	}
}
