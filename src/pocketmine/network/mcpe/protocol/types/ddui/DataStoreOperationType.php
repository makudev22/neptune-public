<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\ddui;

use pocketmine\network\mcpe\protocol\types\PacketIntEnumTrait;

enum DataStoreOperationType : int{
	use PacketIntEnumTrait;

	case UPDATE = 0;
	case CHANGE = 1;
	case REMOVAL = 2;
}
