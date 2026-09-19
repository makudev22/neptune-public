<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pocketmine\network\mcpe\protocol\types\PacketIntEnumTrait;

enum InventoryLeftTab : int
{
	use PacketIntEnumTrait;

	case NONE = 0;
	case CONSTRUCTION = 1;
	case EQUIPMENT = 2;
	case ITEMS = 3;
	case NATURE = 4;
	case SEARCH = 5;
	case SURVIVAL = 6;
}
