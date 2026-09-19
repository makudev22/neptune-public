<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Boat extends Item
{
	public function getFuelTime() : int
	{
		return 1200; //400 in PC
	}

	public function getMaxStackSize() : int
	{
		return 1;
	}

	public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector) : bool
	{
		$nbt = Entity::createBaseNBT($blockReplace->add(0.5, 1, 0.5));
		$nbt->setInt("Variant", $this->getDamage());
		$entity = Entity::createEntity("Boat", $player->level, $nbt);
		$entity->spawnToAll();

		$this->pop();

		return true;
	}
}
