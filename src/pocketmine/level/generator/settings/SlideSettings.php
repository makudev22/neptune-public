<?php


declare(strict_types=1);

namespace pocketmine\level\generator\settings;

class SlideSettings {
	public function __construct(
		private int $target,
		private int $size,
		private int $offset
	){}

	public function getTarget() : int {
		return $this->target;

	}
	public function getSize() : int {
		return $this->size;
	}

	public function getOffset() : int {
		return $this->offset;
	}
}
