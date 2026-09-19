<?php


declare(strict_types=1);

namespace pocketmine\item;

class GoldChestplate extends Armor
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::GOLD_CHESTPLATE, $meta, "Gold Chestplate");
	}

	public function getDefensePoints() : int
	{
		return 5;
	}

	public function getMaxDurability() : int
	{
		return 113;
	}

	public function getEnchantAbility() : int{
		return 25;
	}

	public function getArmorSlot() : int
	{
		return ArmorSlot::SLOT_CHESTPLATE;
	}
}
