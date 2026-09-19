<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\level\ChunkManager;
use pocketmine\level\generator\feature\Feature;
use pocketmine\level\generator\feature\FeaturePlaceContext;
use pocketmine\level\generator\Generator;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class PlacedFeature {
	/**
	 * @param PlacementModifier[] $placement
	 */
	public function __construct(
		public Feature $feature,
		public array $placement
	){}

	public function place(ChunkManager $level, Generator $generator, Random $random, Vector3 $origin) : bool {
		return $this->placeWithContext(new PlacementContext($level, $generator), $random, $origin);
	}

	public function placeWithContext(PlacementContext $context, Random $random, Vector3 $origin) : bool {
		$placements = [$origin];

		foreach ($this->placement as $modifier) {
			$newPlacements = [];
			foreach ($placements as $pos) {
				$positions = $modifier->getPositions($context, $random, $pos);
				foreach ($positions as $newPos) {
					$newPlacements[] = $newPos;
				}
			}
			$placements = $newPlacements;
		}

		$feature = $this->feature;
		$placedAny = false;

		foreach ($placements as $pos) {
			if ($feature->place(new FeaturePlaceContext($context->getLevel(), $random, $pos))) {
				$placedAny = true;
			}
		}

		return $placedAny;
	}
}
