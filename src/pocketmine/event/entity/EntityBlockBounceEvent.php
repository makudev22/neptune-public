<?php


declare(strict_types=1);

namespace pocketmine\event\entity;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;

class EntityBlockBounceEvent extends EntityEvent implements Cancellable
{
	public function __construct(
		Entity $entity,
		protected readonly Block $block,
		protected float $motionMultiplier,
		protected float $fallDistanceMultiplier
	) {
		parent::__construct($entity);
	}

	public function getBlock() : Block
	{
		return $this->block;
	}

	public function getMotionMultiplier() : float
	{
		return $this->motionMultiplier;
	}

	public function setMotionMultiplier(float $motionMultiplier) : void
	{
		$this->motionMultiplier = $motionMultiplier;
	}

	public function getFallDistanceMultiplier() : float
	{
		return $this->fallDistanceMultiplier;
	}

	public function setFallDistanceMultiplier(float $fallDistanceMultiplier) : void
	{
		$this->fallDistanceMultiplier = $fallDistanceMultiplier;
	}
}
