<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\convert;

/**
 * Thrown by TypeConverter if a problem occurred during converting of network types to PM core types (e.g. invalid item
 * ID, invalid NBT, etc).
 */
final class TypeConversionException extends \RuntimeException
{
	public static function wrap(\Throwable $previous, ?string $prefix = null) : self
	{
		return new self(($prefix !== null ? $prefix . ": " : "") . $previous->getMessage(), 0, $previous);
	}
}
