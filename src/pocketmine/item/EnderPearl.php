<?php


declare(strict_types=1);

namespace pocketmine\item;

class EnderPearl extends ProjectileItem
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::ENDER_PEARL, $meta, "Ender Pearl");
	}

	public function getMaxStackSize() : int
	{
		return 16;
	}

	public function getProjectileEntityType() : string
	{
		return "ThrownEnderpearl";
	}

	public function getThrowForce() : float
	{
		return 1.5;
	}

	public function getCooldownTicks() : int
	{
		return 20;
	}
}
