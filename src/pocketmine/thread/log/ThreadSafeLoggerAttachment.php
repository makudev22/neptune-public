<?php


declare(strict_types=1);

namespace pocketmine\thread\log;

use pmmp\thread\ThreadSafe;

abstract class ThreadSafeLoggerAttachment extends ThreadSafe
{
	abstract public function log(string $level, string $message) : void;
}
