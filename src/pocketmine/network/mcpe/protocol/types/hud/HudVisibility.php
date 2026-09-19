<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\hud;

use pocketmine\network\mcpe\protocol\types\PacketIntEnumTrait;

enum HudVisibility : int
{
	use PacketIntEnumTrait;

	case HIDE = 0;
	case RESET = 1;
}
