<?php


declare(strict_types=1);

namespace pocketmine\item;

class Snowball extends ProjectileItem
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::SNOWBALL, $meta, "Snowball");
	}

	public function getMaxStackSize() : int
	{
		return 16;
	}

	public function getProjectileEntityType() : string
	{
		return "Snowball";
	}

	public function getThrowForce() : float
	{
		return 1.5;
	}
}
