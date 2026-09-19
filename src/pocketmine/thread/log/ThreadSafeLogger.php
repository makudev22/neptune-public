<?php


declare(strict_types=1);

namespace pocketmine\thread\log;

use pmmp\thread\ThreadSafe;

abstract class ThreadSafeLogger extends ThreadSafe implements \Logger
{
}
