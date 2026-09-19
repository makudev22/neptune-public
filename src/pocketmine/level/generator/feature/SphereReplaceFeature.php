<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

use pocketmine\block\Water;

class SphereReplaceFeature extends AbstractSphereReplaceFeature {

	public function place(FeaturePlaceContext $context) : bool{
		$origin = $context->origin();
		return ($context->level()->getBlockAt($origin->getFloorX(), $origin->getFloorY(), $origin->getFloorZ()) instanceof Water) && parent::place($context);
	}
}
