<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

class Pufferfish extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::PUFFERFISH, $meta, "Pufferfish");
	}

	public function getFoodRestore() : int
	{
		return 1;
	}

	public function getSaturationRestore() : float
	{
		return 0.2;
	}

	public function getAdditionalEffects() : array
	{
		return [
			new EffectInstance(Effect::getEffect(Effect::HUNGER), 300, 2),
			new EffectInstance(Effect::getEffect(Effect::POISON), 1200, 3),
			new EffectInstance(Effect::getEffect(Effect::NAUSEA), 300, 1)
		];
	}
}
