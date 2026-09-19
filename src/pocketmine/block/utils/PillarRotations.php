<?php


declare(strict_types=1);

namespace pocketmine\block\utils;

use pocketmine\math\Axis;

class PillarRotations{
	public function __construct(
		private int $metaAxisX,
		private int $metaAxisY,
		private int $metaAxisZ
	){}

	public function getMetaAxisX() : int {
		return $this->metaAxisX;
	}

	public function getMetaAxisY() : int {
		return $this->metaAxisY;
	}

	public function getMetaAxisZ() : int {
		return $this->metaAxisZ;
	}

	public function fromAxis(int $axis) : int {
		return match($axis) {
			Axis::X => $this->metaAxisX,
			Axis::Y => $this->metaAxisY,
			Axis::Z => $this->metaAxisZ,
			default => throw new \InvalidArgumentException("Invalid axis $axis")
		};
	}
}
