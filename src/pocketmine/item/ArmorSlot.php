<?php


declare(strict_types=1);

namespace pocketmine\item;

interface ArmorSlot
{
	public const int SLOT_HELMET = 0;
	public const int SLOT_CHESTPLATE = 1;
	public const int SLOT_LEGGINGS = 2;
	public const int SLOT_BOOTS = 3;

	public function getArmorSlot() : int;

}
