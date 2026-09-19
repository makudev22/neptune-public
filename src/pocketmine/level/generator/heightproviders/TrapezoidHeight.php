<?php


declare(strict_types=1);

namespace pocketmine\level\generator\heightproviders;

use pocketmine\level\ChunkManager;
use pocketmine\level\generator\MathHelper;
use pocketmine\level\generator\verticalanchor\VerticalAnchor;
use pocketmine\utils\Random;

class TrapezoidHeight implements HeightProvider {

	public static function of(VerticalAnchor $minInclusive, VerticalAnchor $maxInclusive, int $plateau) : TrapezoidHeight {
		return new self($minInclusive, $maxInclusive, $plateau);
	}

	public function __construct(
		private VerticalAnchor $minInclusive,
		private VerticalAnchor $maxInclusive,
		private int $plateau
	){}

	public function sample(Random $random, ChunkManager $level) : int {
		$min = $this->minInclusive->resolveY($level);
		$max = $this->maxInclusive->resolveY($level);
		if ($min > $max) {
			return $min;
		} else {
			$range = $max - $min;
			if ($this->plateau >= $range) {
				return MathHelper::randomBetweenInclusive($random, $min, $max);
			} else {
				$plateauStart = ($range - $this->plateau) / 2;
				$plateauEnd = $range - $plateauStart;
				return $min + MathHelper::randomBetweenInclusive($random, 0, $plateauEnd) + MathHelper::randomBetweenInclusive($random, 0, $plateauStart);
			}
		}
	}
}
