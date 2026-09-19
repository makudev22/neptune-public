<?php


declare(strict_types=1);

namespace pocketmine\level\generator\heightproviders;

use pocketmine\level\ChunkManager;
use pocketmine\level\generator\MathHelper;
use pocketmine\level\generator\verticalanchor\VerticalAnchor;
use pocketmine\utils\Random;

class VeryBiasedToBottomHeight implements HeightProvider {

	public static function of(VerticalAnchor $minInclusive, VerticalAnchor $maxInclusive, int $inner) : VeryBiasedToBottomHeight {
		return new self($minInclusive, $maxInclusive, $inner);
	}

	public function __construct(
		private VerticalAnchor $minInclusive,
		private VerticalAnchor $maxInclusive,
		private int $inner
	){}

	public function sample(Random $random, ChunkManager $level) : int {
		$min = $this->minInclusive->resolveY($level);
		$max = $this->maxInclusive->resolveY($level);
		if ($max - $min - $this->inner + 1 <= 0) {
			return $min;
		} else {
			$upperInclusive = MathHelper::nextInt($random, $min + $this->inner, $max);
			$biasedUpperInclusive = MathHelper::nextInt($random, $min, $upperInclusive - 1);
			return MathHelper::nextInt($random, $min, $biasedUpperInclusive - 1 + $this->inner);
		}
	}
}
