<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use pocketmine\utils\valueproviders\ConstantInt;
use pocketmine\utils\valueproviders\IntProvider;

class RandomOffsetPlacement extends PlacementModifier {

	public static function of(IntProvider $xzSpread, IntProvider $ySpread) : RandomOffsetPlacement {
		return new RandomOffsetPlacement($xzSpread, $ySpread);
	}

	public static function vertical(IntProvider $ySpread) : RandomOffsetPlacement {
		return new RandomOffsetPlacement(ConstantInt::of(0), $ySpread);
	}

	public static function horizontal(IntProvider $xzSpread) : RandomOffsetPlacement {
		return new RandomOffsetPlacement($xzSpread, 0);
	}

	public function __construct(
		private IntProvider $xzSpread,
		private IntProvider $ySpread
	){}

	/**
	 * @return Vector3[]
	 */
	public function getPositions(PlacementContext $context, Random $random, Vector3 $origin) : array {
		$scatterX = $origin->getX() + $this->xzSpread->sample($random);
		$scatterY = $origin->getY() + $this->ySpread->sample($random);
		$scatterZ = $origin->getZ() + $this->xzSpread->sample($random);
		return [new Vector3($scatterX, $scatterY, $scatterZ)];
	}
}
