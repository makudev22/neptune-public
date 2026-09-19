<?php


declare(strict_types=1);

namespace pocketmine\level\generator;

enum GenerationStageDecoration : int {
	case RAW_GENERATION = 0;
	case LAKES = 1;
	case LOCAL_MODIFICATIONS = 2;
	case UNDERGROUND_STRUCTURES = 3;
	case SURFACE_STRUCTURES = 4;
	case STRONGHOLDS = 5;
	case UNDERGROUND_ORES = 6;
	case UNDERGROUND_DECORATION = 7;
	case VEGETAL_DECORATION = 8;
	case TOP_LAYER_MODIFICATION = 9;
}
