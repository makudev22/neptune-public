<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

class GoldenAppleEnchanted extends GoldenApple
{
	public function __construct(int $meta = 0)
	{
		Food::__construct(self::ENCHANTED_GOLDEN_APPLE, $meta, "Enchanted Golden Apple"); //skip parent constructor
	}

	public function getAdditionalEffects() : array
	{
		return [
			new EffectInstance(Effect::getEffect(Effect::REGENERATION), 600, 4),
			new EffectInstance(Effect::getEffect(Effect::ABSORPTION), 2400, 3),
			new EffectInstance(Effect::getEffect(Effect::RESISTANCE), 6000),
			new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 6000)
		];
	}
}
