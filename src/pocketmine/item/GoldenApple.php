<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

class GoldenApple extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::GOLDEN_APPLE, $meta, "Golden Apple");
	}

	public function requiresHunger() : bool
	{
		return false;
	}

	public function getFoodRestore() : int
	{
		return 4;
	}

	public function getSaturationRestore() : float
	{
		return 9.6;
	}

	public function getAdditionalEffects() : array
	{
		return [
			new EffectInstance(Effect::getEffect(Effect::REGENERATION), 100, 1),
			new EffectInstance(Effect::getEffect(Effect::ABSORPTION), 2400)
		];
	}
}
