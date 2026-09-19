<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\level\sound\ArmorEquipDiamondSound;
use pocketmine\level\sound\Sound;
use pocketmine\math\Vector3;

class DiamondLeggings extends Armor
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::DIAMOND_LEGGINGS, $meta, "Diamond Leggings");
	}

	public function getDefensePoints() : int
	{
		return 6;
	}

	public function getMaxDurability() : int
	{
		return 496;
	}

	public function getEnchantAbility() : int{
		return 10;
	}

	public function getArmorSlot() : int
	{
		return ArmorSlot::SLOT_LEGGINGS;
	}

	public function getEquipSound(Vector3 $vector3) : ?Sound
	{
		return new ArmorEquipDiamondSound($vector3);
	}
}
