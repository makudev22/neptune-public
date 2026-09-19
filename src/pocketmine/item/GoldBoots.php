<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\level\sound\ArmorEquipGoldSound;
use pocketmine\level\sound\Sound;
use pocketmine\math\Vector3;

class GoldBoots extends Armor
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::GOLD_BOOTS, $meta, "Gold Boots");
	}

	public function getDefensePoints() : int
	{
		return 1;
	}

	public function getMaxDurability() : int
	{
		return 92;
	}

	public function getEnchantAbility() : int{
		return 25;
	}

	public function getArmorSlot() : int
	{
		return ArmorSlot::SLOT_BOOTS;
	}

	public function getEquipSound(Vector3 $vector3) : ?Sound
	{
		return new ArmorEquipGoldSound($vector3);
	}
}
