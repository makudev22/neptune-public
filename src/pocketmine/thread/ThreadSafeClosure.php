<?php


declare(strict_types=1);

namespace pocketmine\thread;

use Closure;
use pmmp\thread\ThreadSafe;

final class ThreadSafeClosure extends ThreadSafe{

	private Closure $closure;

	public function __construct(Closure $closure, Object $object = null){
		$closure = Closure::bind($closure, $object === null ? $this : $object);
		$this->closure = $closure;
	}

	public function getClosure() : Closure{
		return $this->closure;
	}

	/**
	 * @deprecated Use bindToAndExecute() instead
	 */
	public function execute(mixed ...$args) : mixed{
		return ($this->closure)(...$args);
	}

	public function bindToAndExecute(Object $newThis, mixed ...$args) : mixed{
		$closure = Closure::bind($this->closure, $newThis);
		return $closure(...$args);
	}
}
