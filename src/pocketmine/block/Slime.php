<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\entity\Entity;

class Slime extends Solid
{
	protected $id = self::SLIME_BLOCK;

	public function __construct()
	{

	}

	public function hasEntityCollision() : bool
	{
		return true;
	}

	public function getHardness() : float
	{
		return 0;
	}

	public function getFrictionFactor() : float
	{
		return 0.8;
	}

	public function getName() : string
	{
		return "Slime Block";
	}

	public function onEntityCollideUpon(Entity $entity) : void
	{
		$entity->resetFallDistance();
	}

	public function getBounceMotionMultiplier() : float
	{
		return 1.0;
	}

	public function getBounceFallDistanceMultiplier() : float
	{
		return 0.0;
	}

	public function onEntityFallenUpon(Entity $entity, float $fallDistance) : void
	{
		if ($entity->isSneaking()) {
			parent::onEntityFallenUpon($entity, $fallDistance);
		} else {
			$entity->fall($fallDistance);
		}
	}
}
