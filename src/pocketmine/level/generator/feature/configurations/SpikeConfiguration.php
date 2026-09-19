<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\level\generator\feature\EndSpike;

class SpikeConfiguration implements FeatureConfiguration {

	/**
	 * @param EndSpike[] $spikes
	 */
	public function __construct(
		public array $spikes
	){}
}
