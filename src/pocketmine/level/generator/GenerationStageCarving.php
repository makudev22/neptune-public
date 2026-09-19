<?php


declare(strict_types=1);

namespace pocketmine\level\generator;

enum GenerationStageCarving : int {
	case AIR = 0;
	case LIQUID = 1;
}
