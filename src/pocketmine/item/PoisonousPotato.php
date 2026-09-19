<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

use function mt_rand;

class PoisonousPotato extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::POISONOUS_POTATO, $meta, "Poisonous Potato");
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
		if (mt_rand(0, 100) > 40) {
			return [
				new EffectInstance(Effect::getEffect(Effect::POISON), 100)
			];
		}
		return [];
	}
}
