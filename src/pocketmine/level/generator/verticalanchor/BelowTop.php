<?php


declare(strict_types=1);

namespace pocketmine\level\generator\verticalanchor;

use pocketmine\level\ChunkManager;
use pocketmine\level\Level;

class BelowTop extends VerticalAnchor{

	public function __construct(private readonly int $offset) {}

	public function resolveY(ChunkManager $level) : int{
		return $level->getWorldHeight() - 1 + Level::Y_MIN - $this->offset;
	}

	public function toString() : string{
		return $this->offset . " below top";
	}

	public function toArray() : array{
		return ['below_top' => $this->offset];
	}

	public function offset() : int{
		return $this->offset;
	}
}
