<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pocketmine\network\mcpe\protocol\types\PacketIntEnumTrait;

enum TriggerType : int
{
	use PacketIntEnumTrait;

	case UNKNOWN = 0;
	case PLAYER_INPUT = 1;
	case SIMULATION_TICK = 2;
}
