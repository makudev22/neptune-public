<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\level\sound\ArmorEquipElytraSound;
use pocketmine\level\sound\Sound;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Elytra extends Durable implements ArmorSlot
{

	public function __construct(int $meta = 0)
	{
		parent::__construct(Item::ELYTRA, $meta, "Elytra Wings");
	}

	public function getMaxDurability() : int
	{
		return 431;
	}

	public function getMaxStackSize() : int
	{
		return 1;
	}

	public function getArmorSlot() : int
	{
		return ArmorSlot::SLOT_CHESTPLATE;
	}

	public function getEquipSound(Vector3 $vector3) : ?Sound
	{
		return new ArmorEquipElytraSound($vector3);
	}

	public function onClickAir(Player $player, Vector3 $directionVector) : bool
	{
		$existing = $player->getArmorInventory()->getItem($this->getArmorSlot());
		$thisCopy = clone $this;
		$new = $thisCopy->pop();
		$player->getArmorInventory()->setItem($this->getArmorSlot(), $new);
		$player->getInventory()->setItemInHand($existing);
		$sound = $new->getEquipSound($player);
		if ($sound !== null) {
			$player->broadcastSound($sound);
		}
		if (!$thisCopy->isNull()) {
			//if the stack size was bigger than 1 (usually won't happen, but might be caused by plugins)
			$this->addReturnedItem($thisCopy);
		}
		return true;
	}
}
