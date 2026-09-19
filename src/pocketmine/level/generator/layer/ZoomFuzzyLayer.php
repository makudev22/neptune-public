<?php


declare(strict_types=1);

namespace pocketmine\level\generator\layer;

use pocketmine\level\generator\ExtendedNoiseRandom;

class ZoomFuzzyLayer extends ZoomLayer {

	public function pickZoomed(ExtendedNoiseRandom $context, int $first, int $second, int $third, int $fourth) : int{
		return $context->pickRandom4($first, $second, $third, $fourth);
	}
}
