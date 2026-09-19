<?php


declare(strict_types=1);

namespace pocketmine\item;

class Egg extends ProjectileItem
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::EGG, $meta, "Egg");
	}

	public function getMaxStackSize() : int
	{
		return 16;
	}

	public function getProjectileEntityType() : string
	{
		return "Egg";
	}

	public function getThrowForce() : float
	{
		return 1.5;
	}
}
