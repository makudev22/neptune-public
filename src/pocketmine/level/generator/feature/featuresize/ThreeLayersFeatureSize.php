<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\featuresize;

class ThreeLayersFeatureSize extends FeatureSize {

	public function __construct(
		private int $limit,
		private int $upperLimit,
		private int $lowerSize,
		private int $middleSize,
		private int $upperSize,
		?int $minClippedHeight = null
	){
		parent::__construct($minClippedHeight);
	}

	protected function type() : FeatureSizeType {
		return FeatureSizeType::THREE_LAYERS_FEATURE_SIZE;
	}

	public function getSizeAtHeight(int $treeHeight, int $yo) : int {
		if ($yo < $this->limit) {
			return $this->lowerSize;
		} else {
			return $yo >= $treeHeight - $this->upperLimit ? $this->upperSize : $this->middleSize;
		}
	}
}
