<?php


declare(strict_types=1);

namespace pocketmine\event\entity;

use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;

class EntityDataPropertyChangeEvent extends EntityEvent implements Cancellable
{
	public function __construct(
		Entity $entity,
		protected readonly int $id,
		protected readonly int $type,
		protected mixed $value,
		protected bool $force
	) {
		parent::__construct($entity);
	}

	public function getId() : int
	{
		return $this->id;
	}

	public function getValue() : mixed
	{
		return $this->value;
	}

	public function setValue(mixed $value) : void
	{
		$this->value = $value;
	}

	public function isSend() : bool
	{
		return $this->force;
	}

	public function setSend(bool $force) : void
	{
		$this->force = $force;
	}
}
