<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

enum ArmorSlot : int{
	use PacketIntEnumTrait;

	case HEAD = 0;
	case TORSO = 1;
	case LEGS = 2;
	case FEET = 3;
	case BODY = 4;
}
