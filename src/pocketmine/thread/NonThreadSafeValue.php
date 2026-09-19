<?php


declare(strict_types=1);

namespace pocketmine\thread;

use pmmp\thread\ThreadSafe;

use function get_debug_type;
use function igbinary_serialize;
use function igbinary_unserialize;

/**
 * This class automatically serializes values which can't be shared between threads.
 * This class does NOT enable sharing the variable between threads. Each call to deserialize() will return a new copy
 * of the variable.
 *
 * @phpstan-template TValue
 */
final class NonThreadSafeValue extends ThreadSafe
{
	private string $variable;

	/**
	 * @phpstan-param TValue $variable
	 */
	public function __construct(
		mixed $variable
	) {
		$this->variable = igbinary_serialize($variable) ?? throw new \InvalidArgumentException("Cannot serialize variable of type " . get_debug_type($variable));
	}

	/**
	 * Returns a deserialized copy of the original variable.
	 *
	 * @phpstan-return TValue
	 */
	public function deserialize() : mixed
	{
		return igbinary_unserialize($this->variable);
	}
}
