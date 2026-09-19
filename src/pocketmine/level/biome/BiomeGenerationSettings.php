<?php


declare(strict_types=1);

namespace pocketmine\level\biome;

use pocketmine\level\generator\feature\Feature;
use pocketmine\level\generator\feature\FlowersFeature;
use pocketmine\level\generator\placement\PlacedFeature;
use pocketmine\level\generator\surfacebuilders\ConfiguredSurfaceBuilder;

class BiomeGenerationSettings {

	/** @var array<Feature> */
	private array $flowerFeatures = [];

	/**
	 * @param array<int, array<PlacedFeature>> $features
	 */
	public function __construct(
		private ConfiguredSurfaceBuilder $surfaceBuilder,
		private array $features
	){
		foreach ($this->features as $stage => $listFeatures) {
			foreach ($listFeatures as $placedFeature) {
				$feature = $placedFeature->feature;
				if ($feature instanceof FlowersFeature) {
					$this->flowerFeatures[] = $feature;
				}
			}
		}
	}

	public function getSurfaceBuilder() : ConfiguredSurfaceBuilder {
		return $this->surfaceBuilder;
	}

	public function getFeatures() : array {
		return $this->features;
	}

	public function getFlowerFeatures() : array {
		return $this->flowerFeatures;
	}
}
