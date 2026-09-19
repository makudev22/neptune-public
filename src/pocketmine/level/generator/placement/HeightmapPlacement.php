<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class HeightmapPlacement extends PlacementModifier {

	public function __construct(
		private HeightmantType $type
	){}

	public function getPositions(PlacementContext $context, Random $random, Vector3 $origin) : array{
		$x = $origin->getX();
		$z = $origin->getZ();
		$height = $this->type->getHighestWorkableBlock($context->getLevel(), $x, $z);
		return $height !== -1 ? [new Vector3($x, $height, $z)] : [];
	}
}
