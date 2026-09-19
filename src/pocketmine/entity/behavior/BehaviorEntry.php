<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

final class BehaviorEntry
{
	/** @var int */
	protected $priority;
	/** @var Behavior */
	protected $behavior;

	public function __construct(int $priority, Behavior $behavior)
	{
		$this->priority = $priority;
		$this->behavior = $behavior;
	}

	public function getPriority() : int
	{
		return $this->priority;
	}

	public function getBehavior() : Behavior
	{
		return $this->behavior;
	}
}
