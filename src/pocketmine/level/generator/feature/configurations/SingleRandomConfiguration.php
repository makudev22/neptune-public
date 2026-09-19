<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\level\generator\feature\Feature;

class SingleRandomConfiguration implements FeatureConfiguration {
	/**
	 * @param Feature[] $features
	 */
	public function __construct(
		public array $features
	){}
}
