<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\featuresize;

abstract class FeatureSize {
	protected const int MAX_WIDTH = 16;

	public function __construct(private ?int $minClippedHeight){}

	abstract protected function type() : FeatureSizeType;

	abstract public function getSizeAtHeight(int $treeHeight, int $yo) : int;

	public function minClippedHeight() : ?int {
		return $this->minClippedHeight;
	}
}
