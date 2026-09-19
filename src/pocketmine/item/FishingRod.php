<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\entity\Entity;
use pocketmine\entity\projectile\FishingHook;
use pocketmine\event\player\PlayerFishEvent;
use pocketmine\math\Vector3;
use pocketmine\Player;

class FishingRod extends Tool
{
	public function __construct()
	{
		parent::__construct(self::FISHING_ROD, 0, "Fishing Rod");
	}

	public function getMaxStackSize() : int
	{
		return 1;
	}

	public function getMaxDurability() : int
	{
		return 384;
	}

	public function getEnchantAbility() : int{
		return 1;
	}

	public function onClickAir(Player $player, Vector3 $directionVector) : bool
	{
		if ($player->getFishingHook() === null) {
			$hook = new FishingHook($player->level, Entity::createBaseNBT($player->add(0, $player->getEyeHeight() - 0.1, 0), $player->getDirectionVector()->multiply(0.4)), $player);
			($ev = new PlayerFishEvent($player, $hook, PlayerFishEvent::STATE_FISHING, null, null, null))->call();
			if ($ev->isCancelled()) {
				$hook->flagForDespawn();
			} else {
				$hook->spawnToAll();
			}
			return true;
		} else {
			$hook = $player->getFishingHook();
			$hook->handleHookRetraction();
			$this->applyDamage(1);
		}

		return true;
	}
}
