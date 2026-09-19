<?php


declare(strict_types=1);

namespace pocketmine\level\biome;

use pocketmine\level\generator\noise\synth\PerlinSimplexNoise;
use pocketmine\utils\Random;
use pocketmine\utils\SingletonTrait;

class BiomeNoise{
	use SingletonTrait;

	private PerlinSimplexNoise $infoNoise;

	public function __construct(){
		$this->infoNoise = new PerlinSimplexNoise(new Random(2345), 1);
	}

	public function getInfoNoise() : PerlinSimplexNoise{
		return $this->infoNoise;
	}
}
