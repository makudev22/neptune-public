<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\level\format\Chunk;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class FixedPlacement extends PlacementModifier {

	/**
	 * @param Vector3[] $positions
	 */
	public function __construct(
		private array $positions
	){}

	public function getPositions(PlacementContext $context, Random $random, Vector3 $origin) : array{
		$chunkX = $origin->getX() >> Chunk::COORD_BIT_SIZE;
		$chunkZ = $origin->getZ() >> Chunk::COORD_BIT_SIZE;

		$filter = [];
		foreach($this->positions as $position){
			if (self::isSameChunk($chunkX, $chunkZ, $position)) {
				$filter[] = $position;
			}
		}

		return $filter;
	}

	private static function isSameChunk(int $chunkX, int $chunkZ, Vector3 $position) : bool {
		return $chunkX === ($position->getFloorX() >> Chunk::COORD_BIT_SIZE) && $chunkZ === ($position->getFloorZ() >> Chunk::COORD_BIT_SIZE);
	}
}
