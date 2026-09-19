<?php


declare(strict_types=1);

namespace pocketmine\scheduler;

use pocketmine\utils\Utils;

abstract class Task
{
	/** @var TaskHandler|null */
	private $taskHandler = null;

	/**
	 * @return TaskHandler|null
	 */
	final public function getHandler()
	{
		return $this->taskHandler;
	}

	final public function getTaskId() : int
	{
		if ($this->taskHandler !== null) {
			return $this->taskHandler->getTaskId();
		}

		return -1;
	}

	public function getName() : string
	{
		return Utils::getNiceClassName($this);
	}

	final public function setHandler(TaskHandler $taskHandler = null)
	{
		if ($this->taskHandler === null || $taskHandler === null) {
			$this->taskHandler = $taskHandler;
		}
	}

	/**
	 * Actions to execute when run
	 *
	 * @return void
	 */
	abstract public function onRun(int $currentTick);

	/**
	 * Actions to execute if the Task is cancelled
	 */
	public function onCancel()
	{

	}
}
