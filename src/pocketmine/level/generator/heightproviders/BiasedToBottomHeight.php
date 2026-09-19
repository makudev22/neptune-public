<?php


declare(strict_types=1);

namespace pocketmine\level\generator\heightproviders;

use pocketmine\level\ChunkManager;
use pocketmine\level\generator\verticalanchor\VerticalAnchor;
use pocketmine\utils\Random;

class BiasedToBottomHeight implements HeightProvider {

	public static function of(VerticalAnchor $minInclusive, VerticalAnchor $maxInclusive, int $inner) : BiasedToBottomHeight {
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
			$limit = $random->nextBoundedInt($max - $min - $this->inner + 1);
			return $random->nextBoundedInt($limit + $this->inner) + $min;
		}
	}
}
