<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\level\sound\ArmorEquipGoldSound;
use pocketmine\level\sound\Sound;
use pocketmine\math\Vector3;

class GoldLeggings extends Armor
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::GOLD_LEGGINGS, $meta, "Gold Leggings");
	}

	public function getDefensePoints() : int
	{
		return 3;
	}

	public function getMaxDurability() : int
	{
		return 106;
	}

	public function getEnchantAbility() : int{
		return 25;
	}

	public function getArmorSlot() : int
	{
		return ArmorSlot::SLOT_LEGGINGS;
	}

	public function getEquipSound(Vector3 $vector3) : ?Sound
	{
		return new ArmorEquipGoldSound($vector3);
	}
}
