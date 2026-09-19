<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\level\sound\ArmorEquipChainSound;
use pocketmine\level\sound\Sound;
use pocketmine\math\Vector3;

class ChainHelmet extends Armor
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::CHAIN_HELMET, $meta, "Chainmail Helmet");
	}

	public function getDefensePoints() : int
	{
		return 2;
	}

	public function getMaxDurability() : int
	{
		return 166;
	}

	public function getEnchantAbility() : int{
		return 12;
	}

	public function getArmorSlot() : int
	{
		return ArmorSlot::SLOT_HELMET;
	}

	public function getEquipSound(Vector3 $vector3) : ?Sound
	{
		return new ArmorEquipChainSound($vector3);
	}
}
