<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

use pocketmine\block\BlockIds;
use pocketmine\block\Snow;
use pocketmine\math\Facing;

class IcePathFeature extends AbstractSphereReplaceFeature {

	public function place(FeaturePlaceContext $context) : bool{
		$origin = $context->origin();

		$block = $context->level()->getBlockAt($origin->getFloorX(), $origin->getFloorY(), $origin->getFloorZ());
		while ($block->getId() === BlockIds::AIR && $block->getY() > 2) {
			$block = $block->getSide(Facing::DOWN);
		}

		return ($block instanceof Snow) && parent::place($context);
	}
}
