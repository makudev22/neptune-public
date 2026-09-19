<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\level\generator\feature\Feature;
use pocketmine\utils\SingletonTrait;

class PlacementFactory {
	use SingletonTrait;

	/** @var PlacedFeature[] */
	private array $placements = [];

	public function __construct(){
		CavePlacements::bootstrap($this);
		EndPlacements::bootstrap($this);
		MiscOverworldPlacements::bootstrap($this);
		OrePlacements::bootstrap($this);
		VegetationPlacements::bootstrap($this);
	}

	/**
	 * @param PlacementModifier[] $placementModifiers
	 */
	public function register(string $name, Feature $feature, array $placementModifiers) : void {
		$this->placements[$name] = new PlacedFeature($feature, $placementModifiers);
	}

	public function get(string $name) : ?PlacedFeature{
		return $this->placements[$name] ?? null;
	}
}
