<?php


declare(strict_types=1);

namespace pocketmine\block\utils\pattern;

use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;

class PatternHelper{
	public function __construct(
		private Vector3 $frontTopLeft,
		private int     $forwards,
		private int     $up,
		private Level   $level,
		private int     $width,
		private int     $height,
		private int     $depth
	){}

	public function getFrontTopLeft() : Vector3{
		return $this->frontTopLeft;
	}

	public function getForwards() : int{
		return $this->forwards;
	}

	public function getUp() : int{
		return $this->up;
	}

	public function getWidth() : int{
		return $this->width;
	}

	public function getHeight() : int{
		return $this->height;
	}

	public function getDepth() : int{
		return $this->depth;
	}

	public function translateOffset(int $palmOffset, int $thumbOffset, int $fingerOffset) : Block{
		$blockPos = BlockPattern::translateOffset(
			$this->frontTopLeft,
			$this->forwards,
			$this->up,
			$palmOffset,
			$thumbOffset,
			$fingerOffset
		);

		return $this->level->getBlockAt((int) $blockPos->x, (int) $blockPos->y, (int) $blockPos->z);
	}

	public function translateOffsetPosition(int $palmOffset, int $thumbOffset, int $fingerOffset) : Vector3{
		return BlockPattern::translateOffset(
			$this->frontTopLeft,
			$this->forwards,
			$this->up,
			$palmOffset,
			$thumbOffset,
			$fingerOffset
		);
	}

	public function __toString() : string{
		return "PatternHelper(up=" . Facing::toString($this->up)
			. ", forwards=" . Facing::toString($this->forwards)
			. ", frontTopLeft=" . $this->frontTopLeft->__toString()
			. ")";
	}
}
