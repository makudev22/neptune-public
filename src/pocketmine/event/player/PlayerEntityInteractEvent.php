<?php


declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;
use pocketmine\math\Vector3;
use pocketmine\Player;

/**
 * Called when a player interacts an entity.
 */
class PlayerEntityInteractEvent extends PlayerEvent implements Cancellable
{
	public function __construct(
		Player $player,
		private readonly Entity $entity,
		private readonly Vector3 $clickPos
	) {
		$this->player = $player;
	}

	public function getEntity() : Entity
	{
		return $this->entity;
	}

	public function getClickPosition() : Vector3
	{
		return $this->clickPos;
	}
}
