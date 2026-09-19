<?php


declare(strict_types=1);

namespace pocketmine\utils\valueproviders;

use pocketmine\utils\Random;

class UniformInt extends IntProvider {

	public function __construct(
		private int $minInclusive,
		private int $maxInclusive
	){}

	public static function of(int $minInclusive, int $maxInclusive) : UniformInt {
		return new UniformInt($minInclusive, $maxInclusive);
	}

	public function getMinInclusive() : int {
		return $this->minInclusive;
	}

	public function getMaxInclusive() : int {
		return $this->maxInclusive;
	}

	public function sample(Random $random) : int {
		return $random->nextBoundedInt($this->maxInclusive - $this->minInclusive + 1) + $this->minInclusive;
	}

	public function getMinValue() : int {
		return $this->minInclusive;
	}

	public function getMaxValue() : int {
		return $this->maxInclusive;
	}

	public function getType() : IntProviderType {
		return IntProviderType::UNIFORM;
	}
}
