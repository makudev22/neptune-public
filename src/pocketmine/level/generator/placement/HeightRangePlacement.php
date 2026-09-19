<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\level\generator\heightproviders\HeightProvider;
use pocketmine\level\generator\heightproviders\TrapezoidHeight;
use pocketmine\level\generator\heightproviders\UniformHeight;
use pocketmine\level\generator\verticalanchor\VerticalAnchor;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class HeightRangePlacement extends PlacementModifier {

	public static function of(HeightProvider $height) : HeightRangePlacement {
		return new self($height);
	}

	public static function uniform(VerticalAnchor $minInclusive, VerticalAnchor $maxInclusive) : HeightRangePlacement {
		return new self(UniformHeight::of($minInclusive, $maxInclusive));
	}

	public static function triangle(VerticalAnchor $minInclusive, VerticalAnchor $maxInclusive) : HeightRangePlacement {
		return new self(TrapezoidHeight::of($minInclusive, $maxInclusive, 0));
	}

	public function __construct(
		private HeightProvider $height
	){}

	public function getPositions(PlacementContext $context, Random $random, Vector3 $origin) : array{
		return [new Vector3($origin->getFloorX(), $this->height->sample($random, $context->getLevel()), $origin->getFloorZ())];
	}
}
