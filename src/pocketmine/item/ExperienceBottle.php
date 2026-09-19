<?php


declare(strict_types=1);

namespace pocketmine\item;

class ExperienceBottle extends ProjectileItem
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::EXPERIENCE_BOTTLE, $meta, "Bottle o' Enchanting");
	}

	public function getProjectileEntityType() : string
	{
		return "ThrownExpBottle";
	}

	public function getThrowForce() : float
	{
		return 0.7;
	}
}
