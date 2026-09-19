<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

final class CaveFeatures{

	public const MONSTER_ROOM = "monster_room";

	private function __construct(){
		//NOOP
	}

	public static function bootstrap(FeatureFactory $featureFactory) : void {
		$featureFactory->register(self::MONSTER_ROOM, new DungeonsFeature());
	}
}
