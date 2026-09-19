<?php


declare(strict_types=1);

namespace pocketmine\thread;

use pmmp\thread\Thread as NativeThread;
use pocketmine\scheduler\AsyncTask;

/**
 * Specialized Thread class aimed at PocketMine-MP-related usages. It handles setting up autoloading and error handling.
 *
 * Note: You probably don't need a thread unless you're doing something in it that's expected to last a long time (or
 * indefinitely).
 * For CPU-demanding tasks that take a short amount of time, consider using AsyncTasks instead to make better use of the
 * CPU.
 * @see AsyncTask
 */
abstract class Thread extends NativeThread
{
	use CommonThreadPartsTrait;
}
