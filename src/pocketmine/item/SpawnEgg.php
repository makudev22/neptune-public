<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\Mob;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Utils;

class SpawnEgg extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::SPAWN_EGG, $meta, "Spawn Egg");
	}

	public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector) : bool
	{
		$nbt = Entity::createBaseNBT($blockReplace->add(0.5, 0, 0.5), null, Utils::getRandomFloat() * 360, 0);

		if ($this->hasCustomName()) {
			$nbt->setString("CustomName", $this->getCustomName());
		}

		$entity = Entity::createEntity($this->meta, $player->getLevel(), $nbt);

		if ($entity instanceof Entity) {
			if ($entity instanceof Mob) {
				$entity->setImmobile(!Server::getInstance()->mobAiEnabled);
			}
			$this->pop();
			$entity->spawnToAll();
			return true;
		}

		return false;
	}
}
