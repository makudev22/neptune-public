<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\utils\Utils;

class RottenFlesh extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::ROTTEN_FLESH, $meta, "Rotten Flesh");
	}

	public function getFoodRestore() : int
	{
		return 4;
	}

	public function getSaturationRestore() : float
	{
		return 0.8;
	}

	public function getAdditionalEffects() : array
	{
		if (Utils::getRandomFloat() <= 0.8) {
			return [
				new EffectInstance(Effect::getEffect(Effect::HUNGER), 600)
			];
		}

		return [];
	}
}
