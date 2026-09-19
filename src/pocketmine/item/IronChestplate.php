<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\level\sound\ArmorEquipIronSound;
use pocketmine\level\sound\Sound;
use pocketmine\math\Vector3;

class IronChestplate extends Armor
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::IRON_CHESTPLATE, $meta, "Iron Chestplate");
	}

	public function getDefensePoints() : int
	{
		return 6;
	}

	public function getMaxDurability() : int
	{
		return 241;
	}

	public function getEnchantAbility() : int{
		return 9;
	}

	public function getArmorSlot() : int
	{
		return ArmorSlot::SLOT_CHESTPLATE;
	}

	public function getEquipSound(Vector3 $vector3) : ?Sound
	{
		return new ArmorEquipIronSound($vector3);
	}
}
