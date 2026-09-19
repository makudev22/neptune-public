<?php


declare(strict_types=1);

namespace pocketmine\entity\hostile;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

class Stray extends Skeleton
{
	public const NETWORK_ID = self::STRAY;

	public function getName() : string
	{
		return "Stray";
	}

	public function getDrops() : array
	{
		$looting = $this->getLootingLevel();
		$drops = parent::getDrops();
		$drops[] = ItemFactory::get(Item::ARROW, 18, 1 + $looting);
		return $drops;
	}

	public function onCollideWithEntity(Entity $entity) : void
	{
		parent::onCollideWithEntity($entity);

		if ($this->getTargetEntityId() === $entity->getId() && $entity instanceof Living) {
			$entity->addEffect(new EffectInstance(Effect::getEffect(Effect::WITHER), 7 * 20, 1));
		}
	}
}
