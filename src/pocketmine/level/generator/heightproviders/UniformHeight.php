<?php


declare(strict_types=1);

namespace pocketmine\level\generator\heightproviders;

use pocketmine\level\ChunkManager;
use pocketmine\level\generator\MathHelper;
use pocketmine\level\generator\verticalanchor\VerticalAnchor;
use pocketmine\utils\Random;

class UniformHeight implements HeightProvider {

	public static function of(VerticalAnchor $minInclusive, VerticalAnchor $maxInclusive) : UniformHeight {
		return new self($minInclusive, $maxInclusive);
	}

	public function __construct(
		private VerticalAnchor $minInclusive,
		private VerticalAnchor $maxInclusive
	){}

	public function sample(Random $random, ChunkManager $level) : int {
		$min = $this->minInclusive->resolveY($level);
		$max = $this->maxInclusive->resolveY($level);
		if ($min > $max) {
			return $min;
		} else {
			return MathHelper::randomBetweenInclusive($random, $min, $max);
		}
	}
}
