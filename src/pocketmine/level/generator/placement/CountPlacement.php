<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use pocketmine\utils\valueproviders\ConstantInt;
use pocketmine\utils\valueproviders\IntProvider;
use pocketmine\utils\valueproviders\WeightedListInt;
use function abs;

class CountPlacement extends RepeatingPlacement {

	public static function countExtra(int $count, float $chance, int $extra) : CountPlacement {
		$weight = 1.0 / $chance;
		if (abs($weight - (int) $weight) > 1.0E-5) {
			throw new \InvalidArgumentException("Chance data cannot be represented as list weight");
		}

		$array = [];
		for ($i = 0; $i <= (int) ($weight - 1); $i++) {
			$array[] = ConstantInt::of($count);
		}

		$array[] = ConstantInt::of($count + $extra);
		return new CountPlacement(new WeightedListInt($array));
	}

	public static function of(IntProvider $count) : CountPlacement {
		return new self($count);
	}

	public function __construct(
		private IntProvider $count
	){}

	protected function count(Random $random, Vector3 $origin) : int{
		return $this->count->sample($random);
	}
}
