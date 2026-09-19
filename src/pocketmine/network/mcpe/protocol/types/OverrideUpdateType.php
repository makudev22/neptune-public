<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

enum OverrideUpdateType : int
{
	use PacketIntEnumTrait;

	case CLEAR_OVERRIDES = 0;
	case REMOVE_OVERRIDE = 1;
	case SET_INT_OVERRIDE = 2;
	case SET_FLOAT_OVERRIDE = 3;
}
