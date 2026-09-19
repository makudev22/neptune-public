<?php


declare(strict_types=1);

namespace pocketmine\item;

class Pumpkin extends ItemBlock implements ArmorSlot
{
	public function getArmorSlot() : int
	{
		return ArmorSlot::SLOT_HELMET;
	}
}
