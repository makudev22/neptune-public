<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\template;

enum RuleTestType{
	case ALWAYS_TRUE_TEST;
	case BLOCK_TEST;
	case BLOCKSTATE_TEST;
	case RANDOM_BLOCK_TEST;
	case RANDOM_BLOCKSTATE_TEST;
	case BLOCK_CLASS_TEST;
	//TODO: tag
}
