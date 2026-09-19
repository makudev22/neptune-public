<?php


declare(strict_types=1);

namespace pocketmine\level\generator\settings;

class ScalingSettings {
	public function __construct(
		private float $xzScale,
		private float $yScale,
		private float $xzFactor,
		private float $yFactor
	){}

	public function getXzScale() : float {
		return $this->xzScale;
	}

	public function getYScale() : float {
		return $this->yScale;
	}

	public function getXzFactor() : float {
		return $this->xzFactor;
	}

	public function getYFactor() : float {
		return $this->yFactor;
	}
}
