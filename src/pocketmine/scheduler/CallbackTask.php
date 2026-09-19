<?php


declare(strict_types=1);

namespace pocketmine\scheduler;

use function call_user_func_array;

/**
 * Allows the creation of simple callbacks with extra data
 * The last parameter in the callback will be this object
 *
 * If you want to do a task in a Plugin, consider extending PluginTask to your needs
 *
 * @deprecated
 * Do NOT use this anymore, it was deprecated a long time ago at PocketMine
 * and will be removed at some stage in the future.
 */
class CallbackTask extends Task
{
	/** @var callable */
	protected $callable;

	/** @var array */
	protected $args;

	public function __construct(callable $callable, array $args = [])
	{
		$this->callable = $callable;
		$this->args = $args;
		$this->args[] = $this;
	}

	public function getCallable() : callable
	{
		return $this->callable;
	}

	public function onRun(int $currentTick)
	{
		call_user_func_array($this->callable, $this->args);
	}
}
