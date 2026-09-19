<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\level\sound\ArmorEquipNetheriteSound;
use pocketmine\level\sound\Sound;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

class NetheriteLeggings extends Armor
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::NETHERITE_LEGGINGS, $meta, "Netherite Leggings");
	}

	public function getDefensePoints() : int
	{
		return 6;
	}

	public function getMaxDurability() : int
	{
		return 556;
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
		return new ArmorEquipNetheriteSound($vector3);
	}

	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData
	{
		if ($playerProtocol < ProtocolInfo::PROTOCOL_407) {
			return new TranslatedItemData(ItemIds::DIAMOND_LEGGINGS, $this->getDamage(), $this->getName());
		}

		return parent::getItemProtocol($playerProtocol);
	}
}
