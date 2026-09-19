<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pocketmine\network\mcpe\protocol\types\PacketIntEnumTrait;

enum InventoryRightTab : int
{
	use PacketIntEnumTrait;

	case NONE = 0;
	case FULL_SCREEN = 1;
	case CRAFTING = 2;
	case ARMOR = 3;
}
