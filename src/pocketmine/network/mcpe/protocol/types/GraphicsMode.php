<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

enum GraphicsMode : int
{
	use PacketIntEnumTrait;

	case SIMPLE = 0;
	case FANCY = 1;
	case ADVANCED = 2;
	case RAY_TRACED = 3;
}
