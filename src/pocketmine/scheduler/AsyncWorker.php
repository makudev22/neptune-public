<?php


declare(strict_types=1);

namespace pocketmine\scheduler;

use pocketmine\thread\log\ThreadSafeLogger;
use pocketmine\thread\Worker;

use function gc_enable;
use function ini_set;

class AsyncWorker extends Worker
{
	/** @var mixed[] */
	private static array $store = [];

	private ThreadSafeLogger $logger;
	private string $name;
	private int $id;

	public function __construct(ThreadSafeLogger $logger, string $name, int $id)
	{
		$this->logger = $logger;
		$this->name = $name;
		$this->id = $id;
	}

	public function onRun() : void
	{
		\GlobalLogger::set($this->logger);

		gc_enable();
		ini_set("memory_limit", '-1');
	}

	public function getLogger() : ThreadSafeLogger
	{
		return $this->logger;
	}

	public function getThreadName() : string
	{
		return $this->name . " Worker #" . $this->id;
	}

	public function getAsyncWorkerId() : int
	{
		return $this->id;
	}

	/**
	 * Saves mixed data into the worker's thread-local object store. This can be used to store objects which you
	 * want to use on this worker thread from multiple AsyncTasks.
	 *
	 * @param mixed $value
	 */
	public function saveToThreadStore(string $identifier, $value) : void
	{
		self::$store[$identifier] = $value;
	}

	/**
	 * Retrieves mixed data from the worker's thread-local object store.
	 *
	 * Note that the thread-local object store could be cleared and your data might not exist, so your code should
	 * account for the possibility that what you're trying to retrieve might not exist.
	 *
	 * Objects stored in this storage may ONLY be retrieved while the task is running.
	 *
	 * @return mixed
	 */
	public function getFromThreadStore(string $identifier)
	{
		return self::$store[$identifier] ?? null;
	}

	/**
	 * Removes previously-stored mixed data from the worker's thread-local object store.
	 */
	public function removeFromThreadStore(string $identifier) : void
	{
		unset(self::$store[$identifier]);
	}
}
