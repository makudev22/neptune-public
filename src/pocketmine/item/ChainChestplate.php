<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\level\sound\ArmorEquipChainSound;
use pocketmine\level\sound\Sound;
use pocketmine\math\Vector3;

class ChainChestplate extends Armor
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::CHAIN_CHESTPLATE, $meta, "Chain Chestplate");
	}

	public function getDefensePoints() : int
	{
		return 5;
	}

	public function getMaxDurability() : int
	{
		return 241;
	}

	public function getEnchantAbility() : int{
		return 12;
	}

	public function getArmorSlot() : int
	{
		return ArmorSlot::SLOT_CHESTPLATE;
	}

	public function getEquipSound(Vector3 $vector3) : ?Sound
	{
		return new ArmorEquipChainSound($vector3);
	}
}
