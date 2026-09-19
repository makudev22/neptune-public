<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

class SpiderEye extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::SPIDER_EYE, $meta, "Spider Eye");
	}

	public function getFoodRestore() : int
	{
		return 2;
	}

	public function getSaturationRestore() : float
	{
		return 3.2;
	}

	public function getAdditionalEffects() : array
	{
		return [new EffectInstance(Effect::getEffect(Effect::POISON), 80)];
	}
}
