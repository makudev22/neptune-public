<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\level\sound\ArmorEquipLeatherSound;
use pocketmine\level\sound\Sound;
use pocketmine\math\Vector3;

class LeatherCap extends Armor
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::LEATHER_CAP, $meta, "Leather Cap");
	}

	public function getDefensePoints() : int
	{
		return 1;
	}

	public function getMaxDurability() : int
	{
		return 56;
	}

	public function getEnchantAbility() : int{
		return 15;
	}

	public function getArmorSlot() : int
	{
		return ArmorSlot::SLOT_HELMET;
	}

	public function getEquipSound(Vector3 $vector3) : ?Sound
	{
		return new ArmorEquipLeatherSound($vector3);
	}
}
