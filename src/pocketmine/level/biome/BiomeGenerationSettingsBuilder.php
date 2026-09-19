<?php


declare(strict_types=1);

namespace pocketmine\level\biome;

use pocketmine\level\generator\GenerationStageDecoration;
use pocketmine\level\generator\placement\PlacedFeature;
use pocketmine\level\generator\surfacebuilders\ConfiguredSurfaceBuilder;
use function count;

class BiomeGenerationSettingsBuilder {

	private ?ConfiguredSurfaceBuilder $surfaceBuilder = null;
	/** @var array<int, array<PlacedFeature>> */
	private array $features = [];

	public function __construct(){
		//NOOP
	}

	public function withSurfaceBuilder(ConfiguredSurfaceBuilder $configuredSurfaceBuilder) : self {
		$this->surfaceBuilder = $configuredSurfaceBuilder;
		return $this;
	}

	public function withFeature(GenerationStageDecoration $decorationStage, PlacedFeature $feature) : self {
		$this->populateStageEntries($decorationStage->value);
		$this->features[$decorationStage->value][] = $feature;
		return $this;
	}

	private function populateStageEntries(int $stage) : void {
		while(count($this->features) <= $stage) {
			$this->features[] = [];
		}
	}

	public function build() : BiomeGenerationSettings {
		if ($this->surfaceBuilder === null) {
			throw new \InvalidArgumentException("Surface Builder is not defined");
		}

		return new BiomeGenerationSettings($this->surfaceBuilder, $this->features);
	}
}
