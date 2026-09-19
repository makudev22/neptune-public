<?php


declare(strict_types=1);

namespace pocketmine\plugin;

use pocketmine\event\Cancellable;
use pocketmine\event\Event;
use pocketmine\event\Listener;
use pocketmine\timings\TimingsHandler;

class RegisteredListener
{
	public function __construct(
		private Listener $listener,
		private EventExecutor $executor,
		private int $priority,
		private Plugin $plugin,
		private bool $ignoreCancelled,
		private TimingsHandler $timings
	) {
	}

	public function getListener() : Listener
	{
		return $this->listener;
	}

	public function getPlugin() : Plugin
	{
		return $this->plugin;
	}

	public function getPriority() : int
	{
		return $this->priority;
	}

	public function callEvent(Event $event) : void
	{
		if ($event instanceof Cancellable && $event->isCancelled() && $this->isIgnoringCancelled()) {
			return;
		}
		$this->timings->startTiming();
		try {
			$this->executor->execute($this->listener, $event);
		} finally {
			$this->timings->stopTiming();
		}
	}

	public function isIgnoringCancelled() : bool
	{
		return $this->ignoreCancelled;
	}
}
