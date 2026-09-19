<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use InvalidArgumentException;

/**
 * Trait for enums serialized in packets. Provides a convenient helper method to read, validate and properly bail on
 * invalid values.
 */
trait PacketIntEnumTrait
{
	/**
	 * @throws InvalidArgumentException
	 */
	public static function fromPacket(int $value) : self
	{
		$enum = self::tryFrom($value);
		if ($enum === null) {
			throw new InvalidArgumentException("Invalid raw value $value for " . static::class);
		}

		return $enum;
	}
}
