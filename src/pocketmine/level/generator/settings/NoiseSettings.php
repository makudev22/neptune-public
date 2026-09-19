<?php


declare(strict_types=1);

namespace pocketmine\level\generator\settings;

class NoiseSettings {
	public function __construct(
		private int $height,
		private ScalingSettings $sampling,
		private SlideSettings $topSlide,
		private SlideSettings $bottomSlide,
		private int $sizeHorizontal,
		private int $sizeVertical,
		private float $densityFactor,
		private float $densityOffset,
		private bool $simplexFurfaceNoise,
		private bool $randomDensityOffset,
		private bool $islandNoiseOverride,
		private bool $amplified
	){}

	public function getHeight() : int {
		return $this->height;
	}

	public function getSampling() : ScalingSettings {
		return $this->sampling;
	}

	public function getTopSlide() : SlideSettings {
		return $this->topSlide;
	}

	public function getBottomSlide() : SlideSettings {
		return $this->bottomSlide;
	}

	public function getSizeHorizontal() : int {
		return $this->sizeHorizontal;
	}

	public function getSizeVertical() : int {
		return $this->sizeVertical;
	}

	public function getDensityFactor() : float {
		return $this->densityFactor;
	}

	public function getDensityOffset() : float {
		return $this->densityOffset;
	}

	public function getSimplexFurfaceNoise() : bool {
		return $this->simplexFurfaceNoise;
	}

	public function getRandomDensityOffset() : bool {
		return $this->randomDensityOffset;
	}

	public function getIslandNoiseOverride() : bool {
		return $this->islandNoiseOverride;
	}

	public function getAmplified() : bool {
		return $this->amplified;
	}
}
