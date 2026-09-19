<?php


declare(strict_types=1);

namespace pocketmine\entity\hostile;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\entity\Monster;

class Husk extends Zombie
{
	public const NETWORK_ID = self::HUSK;

	public function getName() : string
	{
		return "Husk";
	}

	public function entityBaseTick(int $diff = 1) : bool
	{
		return Monster::entityBaseTick($diff);
	}

	public function getArmorPoints() : int
	{
		return 2;
	}

	public function onCollideWithEntity(Entity $entity) : void
	{
		parent::onCollideWithEntity($entity);

		if ($this->getTargetEntityId() === $entity->getId() && $entity instanceof Living) {
			$entity->addEffect(new EffectInstance(Effect::getEffect(Effect::HUNGER), 7 * 20, 1));
		}
	}
}
