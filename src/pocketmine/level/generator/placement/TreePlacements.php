<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\level\generator\feature\FeatureFactory;

final class TreePlacements{

	private function __construct(){
		//NOOP
	}

	public static function bootstrap(PlacementFactory $placementFactory) : void {
		$features = FeatureFactory::getInstance();

	}
}
