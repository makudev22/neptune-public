<?php


declare(strict_types=1);

namespace pocketmine\level\generator\heightproviders;

use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;
use function count;

class WeightedListHeight implements HeightProvider {
	/**
	 * @param HeightProvider[] $distribution
	 */
	public function __construct(
		private array $distribution
	){}

	public function sample(Random $random, ChunkManager $level) : int {
		return $this->distribution[$random->nextRange(0, count($this->distribution) - 1)]->sample($random, $level);
	}
}
