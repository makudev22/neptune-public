<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

class EndSpike {
	public function __construct(
		public int $centerX,
		public int $centerZ,
		public int $radius,
		public int $height,
		public bool $guarded
	) {}
}
