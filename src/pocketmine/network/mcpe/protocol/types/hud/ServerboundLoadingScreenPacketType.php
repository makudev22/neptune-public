<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\hud;

use pocketmine\network\mcpe\protocol\types\PacketIntEnumTrait;

enum ServerboundLoadingScreenPacketType : int
{
	use PacketIntEnumTrait;

	case UNKNOWN = 0;
	case START_LOADING_SCREEN = 1;
	case STOP_LOADING_SCREEN = 2;
}
