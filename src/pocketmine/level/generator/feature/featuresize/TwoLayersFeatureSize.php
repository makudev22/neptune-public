<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\featuresize;

class TwoLayersFeatureSize extends FeatureSize {

	public function __construct(private int $limit, private int $lowerSize, private int $upperSize, ?int $minClippedHeight = null){
		parent::__construct($minClippedHeight);
	}

	protected function type() : FeatureSizeType {
		return FeatureSizeType::TWO_LAYERS_FEATURE_SIZE;
	}

	public function getSizeAtHeight(int $treeHeight, int $yo) : int {
		return $yo < $this->limit ? $this->lowerSize : $this->upperSize;
	}
}
