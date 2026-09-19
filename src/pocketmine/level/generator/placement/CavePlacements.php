<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\level\generator\feature\CaveFeatures;
use pocketmine\level\generator\feature\FeatureFactory;
use pocketmine\utils\valueproviders\ConstantInt;

final class CavePlacements{

	public const MONSTER_ROOM = "monster_room";

	private function __construct(){
		//NOOP
	}

	public static function bootstrap(PlacementFactory $placementFactory) : void {
		$features = FeatureFactory::getInstance();

		$placementFactory->register(self::MONSTER_ROOM, $features->get(CaveFeatures::MONSTER_ROOM), [
			CountPlacement::of(ConstantInt::of(8)),
			new MonsterRoomPlacement(),
		]);
	}
}
