<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\level\sound\ArmorEquipLeatherSound;
use pocketmine\level\sound\Sound;
use pocketmine\math\Vector3;

class LeatherPants extends Armor
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::LEATHER_PANTS, $meta, "Leather Pants");
	}

	public function getDefensePoints() : int
	{
		return 2;
	}

	public function getMaxDurability() : int
	{
		return 76;
	}

	public function getEnchantAbility() : int{
		return 15;
	}

	public function getArmorSlot() : int
	{
		return ArmorSlot::SLOT_LEGGINGS;
	}

	public function getEquipSound(Vector3 $vector3) : ?Sound
	{
		return new ArmorEquipLeatherSound($vector3);
	}
}
