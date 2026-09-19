<?php


declare(strict_types=1);

namespace pocketmine\level\biome;

use pocketmine\utils\WeightedRandomItem;

class SpawnListEntry extends WeightedRandomItem {
	public string $entityClass;
	public int $minGroupCount = 0;
	public int $maxGroupCount = 0;

	public function __construct(string $entityClass, int $itemWeight, int $minGroupCount, int $maxGroupCount)
	{
		parent::__construct($itemWeight);

		$this->entityClass = $entityClass;
		$this->minGroupCount = $minGroupCount;
		$this->maxGroupCount = $maxGroupCount;
	}
}
