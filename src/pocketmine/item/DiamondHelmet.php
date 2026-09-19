<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\level\sound\ArmorEquipDiamondSound;
use pocketmine\level\sound\Sound;
use pocketmine\math\Vector3;

class DiamondHelmet extends Armor
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::DIAMOND_HELMET, $meta, "Diamond Helmet");
	}

	public function getDefensePoints() : int
	{
		return 3;
	}

	public function getMaxDurability() : int
	{
		return 364;
	}

	public function getEnchantAbility() : int{
		return 10;
	}

	public function getArmorSlot() : int
	{
		return ArmorSlot::SLOT_HELMET;
	}

	public function getEquipSound(Vector3 $vector3) : ?Sound
	{
		return new ArmorEquipDiamondSound($vector3);
	}
}
