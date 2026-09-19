<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

use pocketmine\entity\Animal;

class FollowParentBehavior extends Behavior
{
	protected float $speedMultiplier;
	protected int $delay = 0;
	protected ?Animal $parentAnimal = null;

	public function __construct(Animal $mob, float $speedMultiplier)
	{
		parent::__construct($mob);

		$this->speedMultiplier = $speedMultiplier;
	}

	public function canStart() : bool
	{
		if ($this->mob->isBaby()) {
			$searchDist = 16.0;
			$dist = 9;
			$animal = null;
			foreach ($this->mob->level->getNearbyEntities($this->mob->getBoundingBox()->expandedCopy($searchDist, $searchDist, $searchDist), $this->mob) as $entity) {
				if (!$entity->isBaby()) {
					if (($d2 = $entity->distanceSquared($this->mob)) < $dist) {
						$dist = $d2;
						$animal = $entity;
					}
				}
			}

			if ($animal instanceof Animal) {
				if ($dist >= 9) {
					$this->parentAnimal = $animal;
					return true;
				}
			}
		}

		return false;
	}

	public function canContinue() : bool
	{
		$d = $this->mob->distanceSquared($this->parentAnimal);
		return $this->mob->isBaby() && $this->parentAnimal->isAlive() && $d >= 9 && $d <= 256;
	}

	public function onStart() : void
	{
		$this->delay = 0;
	}

	public function onTick() : void
	{
		if ($this->delay-- <= 0) {
			$this->delay = 10;
			$this->mob->getNavigator()->tryMoveTo($this->parentAnimal, $this->speedMultiplier);
		}
	}

	public function onEnd() : void
	{
		$this->parentAnimal = null;
	}
}
