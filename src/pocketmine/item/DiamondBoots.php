<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\level\sound\ArmorEquipDiamondSound;
use pocketmine\level\sound\Sound;
use pocketmine\math\Vector3;

class DiamondBoots extends Armor
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::DIAMOND_BOOTS, $meta, "Diamond Boots");
	}

	public function getDefensePoints() : int
	{
		return 3;
	}

	public function getMaxDurability() : int
	{
		return 430;
	}

	public function getEnchantAbility() : int{
		return 10;
	}

	public function getArmorSlot() : int
	{
		return ArmorSlot::SLOT_BOOTS;
	}

	public function getEquipSound(Vector3 $vector3) : ?Sound
	{
		return new ArmorEquipDiamondSound($vector3);
	}
}
