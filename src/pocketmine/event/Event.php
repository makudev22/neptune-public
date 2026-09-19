<?php


declare(strict_types=1);

/**
 * Event related classes
 */

namespace pocketmine\event;

use BadMethodCallException;
use pocketmine\Server;
use pocketmine\timings\Timings;
use RuntimeException;

use Throwable;
use function assert;
use function get_class;

abstract class Event
{
	private const MAX_EVENT_CALL_DEPTH = 50;
	/** @var int */
	private static $eventCallDepth = 1;

	/** @var string|null */
	protected $eventName = null;
	/** @var bool */
	private $isCancelled = false;

	final public function getEventName() : string
	{
		return $this->eventName ?? get_class($this);
	}

	/**
	 * @throws BadMethodCallException
	 */
	public function isCancelled() : bool
	{
		if (!($this instanceof Cancellable)) {
			throw new BadMethodCallException(get_class($this) . " is not Cancellable");
		}

		return $this->isCancelled;
	}

	/**
	 * @throws BadMethodCallException
	 */
	public function setCancelled(bool $value = true) : void
	{
		if (!($this instanceof Cancellable)) {
			throw new BadMethodCallException(get_class($this) . " is not Cancellable");
		}

		$this->isCancelled = $value;
	}

	/**
	 * Calls event handlers registered for this event.
	 *
	 * @throws RuntimeException if event call recursion reaches the max depth limit
	 */
	public function call() : void
	{
		if (self::$eventCallDepth >= self::MAX_EVENT_CALL_DEPTH) {
			//this exception will be caught by the parent event call if all else fails
			throw new RuntimeException("Recursive event call detected (reached max depth of " . self::MAX_EVENT_CALL_DEPTH . " calls)");
		}

		$timings = Timings::getEventTimings($this);
		$timings->startTiming();

		$handlerList = HandlerList::getHandlerListFor(get_class($this));
		assert($handlerList !== null, "Called event should have a valid HandlerList");

		++self::$eventCallDepth;
		try {
			foreach (EventPriority::ALL as $priority) {
				$currentList = $handlerList;
				while ($currentList !== null) {
					foreach ($currentList->getListenersByPriority($priority) as $registration) {
						$registration->callEvent($this);
					}

					$currentList = $currentList->getParent();
				}
			}
		} catch (Throwable $e) {
			Server::getInstance()->getLogger()->error("An error occurred while executing " . get_class($this) . " event handler: " . $e->getMessage());
			Server::getInstance()->getLogger()->logException($e);
		} finally {
			--self::$eventCallDepth;
			$timings->stopTiming();
		}
	}
}
