<?php


declare(strict_types=1);

namespace pocketmine\entity\projectile;

use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\level\particle\ItemBreakParticle;

class Egg extends Throwable
{
	public const NETWORK_ID = self::EGG;

	//TODO: spawn chickens on collision

	protected function onHit(ProjectileHitEvent $event) : void
	{
		for ($i = 0; $i < 6; ++$i) {
			$this->level->addParticle(new ItemBreakParticle($this, ItemFactory::get(Item::EGG)));
		}
	}
}
