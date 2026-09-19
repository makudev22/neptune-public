<?php


declare(strict_types=1);

namespace pocketmine\utils\valueproviders;

use pocketmine\utils\Random;
use function max;
use function min;

class ClampedInt extends IntProvider {

	public function __construct(
		private IntProvider $source,
		private int $minInclusive,
		private int $maxInclusive
	){}

	public static function of(IntProvider $source, int $minInclusive, int $maxInclusive) : ClampedInt {
		return new self($source, $minInclusive, $maxInclusive);
	}

	public function sample(Random $random) : int {
		return min(max($this->source->sample($random), $this->minInclusive), $this->maxInclusive);
	}

	public function getMinValue() : int {
		return max($this->minInclusive, $this->source->getMinValue());
	}

	public function getMaxValue() : int {
		return min($this->maxInclusive, $this->source->getMaxValue());
	}

	public function getType() : IntProviderType {
		return IntProviderType::CONSTANT;
	}
}
