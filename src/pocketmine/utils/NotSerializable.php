<?php


declare(strict_types=1);

namespace pocketmine\utils;

use LogicException;

trait NotSerializable
{
	/** @return mixed[] */
	final public function __serialize() : array
	{
		throw new LogicException("Serialization of " . static::class . " objects is not allowed");
	}

	/** @param mixed[] $data */
	final public function __unserialize(array $data) : void
	{
		throw new LogicException("Unserialization of " . static::class . " objects is not allowed");
	}
}
