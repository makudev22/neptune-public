<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\entity\Entity;
use pocketmine\entity\passive\Pig;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;

class Saddle extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::SADDLE, $meta, "Saddle");
	}

	public function getMaxStackSize() : int
	{
		return 1;
	}

	public function onInteractEntity(Player $player, Entity $entity, Vector3 $clickVector) : bool
	{
		if ($entity instanceof Pig) {
			if (!$entity->isSaddled() && !$entity->isBaby()) {
				$entity->setSaddled(true);
				$entity->level->broadcastLevelSoundEvent($entity, LevelSoundEventPacket::SOUND_SADDLE);
				$this->pop();
			}

			return true;
		}
		return false;
	}
}
