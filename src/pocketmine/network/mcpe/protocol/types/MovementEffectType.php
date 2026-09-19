<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

enum MovementEffectType : int
{
	use PacketIntEnumTrait;

	case INVALID = -1;
	case GLIDE_BOOST = 0;
}
