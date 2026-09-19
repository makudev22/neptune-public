<?php


declare(strict_types=1);

namespace pocketmine\level\generator\verticalanchor;

use pocketmine\level\ChunkManager;

class Absolute extends VerticalAnchor{

	public function __construct(private readonly int $y) {}

	public function resolveY(ChunkManager $level) : int{
		return $this->y;
	}

	public function toString() : string{
		return $this->y . " absolute";
	}

	public function toArray() : array{
		return ['absolute' => $this->y];
	}

	public function y() : int{
		return $this->y;
	}
}
