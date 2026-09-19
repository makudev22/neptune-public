<?php


declare(strict_types=1);

namespace pocketmine\utils;

use SplPriorityQueue;

/**
 * @phpstan-template TPriority
 * @phpstan-template TValue
 * @phpstan-extends SplPriorityQueue<TPriority, TValue>
 */
class ReversePriorityQueue extends SplPriorityQueue
{
	/**
	 * @param mixed $priority1
	 * @param mixed $priority2
	 *
	 * @phpstan-param TPriority $priority1
	 * @phpstan-param TPriority $priority2
	 */
	public function compare($priority1, $priority2) : int
	{
		//TODO: this will crash if non-numeric priorities are used
		return (int) -($priority1 - $priority2);
	}
}
