<?php


declare(strict_types=1);

namespace pocketmine\entity\hostile;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;

class CaveSpider extends Spider
{
	public const NETWORK_ID = self::CAVE_SPIDER;

	public float $width = 0.7;
	public float $height = 0.5;

	protected function initEntity() : void
	{
		parent::initEntity();

		$this->setMaxHealth(12);
		$this->setHealth(12);
	}

	public function getName() : string
	{
		return "CaveSpider";
	}

	public function onCollideWithEntity(Entity $entity) : void
	{
		parent::onCollideWithEntity($entity);

		if ($entity instanceof Living) {
			if ($this->getTargetEntity() === $entity) {
				$entity->addEffect(new EffectInstance(Effect::getEffect(Effect::POISON), 7 * 20, 1));
			}
		}
	}
}
