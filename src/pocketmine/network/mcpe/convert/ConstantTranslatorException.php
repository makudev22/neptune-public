<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\convert;

/**
 * A TypeConverter is generated if it was not found when receiving a special ID.
 */
final class ConstantTranslatorException extends \RuntimeException
{
	public static function wrap(\Throwable $previous, ?string $prefix = null) : self
	{
		return new self(($prefix !== null ? $prefix . ": " : "") . $previous->getMessage(), 0, $previous);
	}
}
