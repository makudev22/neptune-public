<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

use pocketmine\level\generator\feature\configurations\SpikeConfiguration;

final class EndFeatures{

	public const END_PLATFORM = "end_platform";
	public const END_SPIKE = "end_spike";
	public const END_GATEWAY_RETURN = "end_gateway_return";
	public const END_GATEWAY_DELAYED = "end_gateway_delayed";
	public const CHORUS_PLANT = "chorus_plant";
	public const END_ISLAND = "end_island";

	private function __construct(){
		//NOOP
	}

	public static function bootstrap(FeatureFactory $featureFactory) : void {
		$featureFactory->register(self::END_PLATFORM, new EndPlatformFeature());
		$featureFactory->register(self::END_SPIKE, new EndSpikeFeature(new SpikeConfiguration([])));
		$featureFactory->register(self::CHORUS_PLANT, new ChorusPlantFeature());
		$featureFactory->register(self::END_ISLAND, new EndIslandFeature());
	}
}
