<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\stateproviders;

enum BlockStateProviderType {
	case SIMPLE_STATE_PROVIDER;
	case WEIGHTED_STATE_PROVIDER;
	case PLAIN_FLOWER_STATE_PROVIDER;
	case FOREST_FLOWER_STATE_PROVIDER;
	case DUAL_NOISE_PROVIDER;
	case ROTATED_BLOCK_PROVIDER;
	case RANDOMIZED_INT_STATE_PROVIDER;
}
