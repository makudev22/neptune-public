<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\nbt\tag\CompoundTag;

class SplashPotion extends ProjectileItem
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::SPLASH_POTION, $meta, "Splash Potion");
	}

	public function getMaxStackSize() : int
	{
		return 1;
	}

	public function getProjectileEntityType() : string
	{
		return "ThrownPotion";
	}

	public function getThrowForce() : float
	{
		return 0.5;
	}

	public function getPitchOffset() : float
	{
		return -15;
	}

	protected function addExtraTags(CompoundTag $tag) : void
	{
		$tag->setShort("PotionId", $this->meta);
	}
}
