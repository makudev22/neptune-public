<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pocketmine\network\mcpe\protocol\types\PacketIntEnumTrait;

enum InventoryLayout : int
{
	use PacketIntEnumTrait;

	case NONE = 0;
	case SURVIVAL = 1;
	case RECIPE_BOOK = 2;
	case CREATIVE = 3;
}
