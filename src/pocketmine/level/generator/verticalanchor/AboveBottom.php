<?php


declare(strict_types=1);

namespace pocketmine\level\generator\verticalanchor;

use pocketmine\level\ChunkManager;
use pocketmine\level\Level;

class AboveBottom extends VerticalAnchor{

	public function __construct(private readonly int $offset) {}

	public function resolveY(ChunkManager $level) : int{
		return Level::Y_MIN + $this->offset;
	}

	public function toString() : string{
		return $this->offset . " above bottom";
	}

	public function toArray() : array{
		return ['above_bottom' => $this->offset];
	}

	public function offset() : int{
		return $this->offset;
	}
}
