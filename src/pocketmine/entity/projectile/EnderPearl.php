<?php


declare(strict_types=1);

namespace pocketmine\entity\projectile;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\level\particle\EndermanTeleportParticle;
use pocketmine\level\sound\EndermanTeleportSound;

class EnderPearl extends Throwable
{
	public const NETWORK_ID = self::ENDER_PEARL;

	protected function onHit(ProjectileHitEvent $event) : void
	{
		$owner = $this->getOwningEntity();
		if ($owner !== null) {
			//TODO: check end gateways (when they are added)
			//TODO: spawn endermites at origin

			$this->level->addParticle(new EndermanTeleportParticle($owner));
			$this->level->addSound(new EndermanTeleportSound($owner));
			$owner->teleport($target = $event->getRayTraceResult()->getHitVector());
			$this->level->addSound(new EndermanTeleportSound($target));

			$owner->attack(new EntityDamageEvent($owner, EntityDamageEvent::CAUSE_FALL, 5));
		}
	}
}
