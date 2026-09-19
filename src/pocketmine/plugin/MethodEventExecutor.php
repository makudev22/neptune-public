<?php


declare(strict_types=1);

namespace pocketmine\plugin;

use pocketmine\event\Event;
use pocketmine\event\Listener;

class MethodEventExecutor implements EventExecutor
{
	/** @var string */
	private $method;

	/**
	 * @param string $method
	 */
	public function __construct($method)
	{
		$this->method = $method;
	}

	public function execute(Listener $listener, Event $event)
	{
		$listener->{$this->getMethod()}($event);
	}

	/**
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}
}
