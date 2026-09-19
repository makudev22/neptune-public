<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\raklib;

use pmmp\thread\ThreadSafeArray;
use pocketmine\snooze\SleeperNotifier;
use raklib\server\ipc\InterThreadChannelWriter;

final class SnoozeAwarePthreadsChannelWriter implements InterThreadChannelWriter
{
	/**
	 * @phpstan-param ThreadSafeArray<int, string> $buffer
	 */
	public function __construct(
		private ThreadSafeArray $buffer,
		private SleeperNotifier $notifier
	) {
	}

	public function write(string $str) : void
	{
		$this->buffer[] = $str;
		$this->notifier->wakeupSleeper();
	}
}
