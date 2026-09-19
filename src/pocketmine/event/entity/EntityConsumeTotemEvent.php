<?php


declare(strict_types=1);

namespace pocketmine\event\entity;

use pocketmine\entity\Human;
use pocketmine\event\Cancellable;

class EntityConsumeTotemEvent extends EntityEvent implements Cancellable
{
	public function __construct(Human $consumer)
	{
		$this->entity = $consumer;
	}

	public function getEntity() : Human
	{
		return $this->entity;
	}
}
