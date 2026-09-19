<?php


declare(strict_types=1);

namespace pocketmine\entity\helper;

use pocketmine\entity\Mob;

class EntityJumpHelper
{
	protected $isJumping = false;
	/** @var Mob */
	protected $entity;

	public function __construct(Mob $mob)
	{
		$this->entity = $mob;
	}

	public function isJumping() : bool
	{
		return $this->isJumping;
	}

	public function setJumping(bool $isJumping) : void
	{
		$this->isJumping = $isJumping;
	}

	public function doJump() : void
	{
		$this->entity->setJumping($this->isJumping);
		$this->isJumping = false;
	}
}
