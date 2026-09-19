<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

use function mt_rand;

class RawChicken extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::RAW_CHICKEN, $meta, "Raw Chicken");
	}

	public function getFoodRestore() : int
	{
		return 2;
	}

	public function getSaturationRestore() : float
	{
		return 1.2;
	}

	public function getAdditionalEffects() : array
	{
		return mt_rand(0, 9) < 3 ? [new EffectInstance(Effect::getEffect(Effect::HUNGER), 600)] : [];
	}
}
