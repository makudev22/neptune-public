<?php


declare(strict_types=1);

namespace pocketmine\level\biome\provider;

use pocketmine\level\generator\noise\synth\SimplexNoise;

final class EndBiomeProvider{

	private function __construct(){
	}

	public static function getRandomNoise(SimplexNoise $noise, int $x, int $z) : float{
		return $noise->getValue2D($x, $z);
	}
}
