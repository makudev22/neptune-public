<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

use pocketmine\utils\SingletonTrait;

class FeatureFactory {
	use SingletonTrait;

	/** @var Feature[] */
	private array $features = [];

	public function __construct(){
		//TODO: AquaticFeatures
		CaveFeatures::bootstrap($this);
		EndFeatures::bootstrap($this);
		MiscOverworldFeatures::bootstrap($this);
		//TODO: NetherFeatures
		OreFeatures::bootstrap($this);
		//TODO: PileFeatures
		TreeFeatures::bootstrap($this);
		VegetationFeatures::bootstrap($this);
	}

	public function register(string $name, Feature $feature) : void {
		$this->features[$name] = $feature;
	}

	public function get(string $name) : ?Feature{
		return $this->features[$name] ?? null;
	}
}
