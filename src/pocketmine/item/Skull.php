<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\tile\Skull as TileSkull;

class Skull extends ItemBlock implements ArmorSlot
{
	public function getArmorSlot() : int
	{
		return ArmorSlot::SLOT_HELMET;
	}

	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData
	{
		if ($playerProtocol < ProtocolInfo::PROTOCOL_567 && $this->meta === TileSkull::TYPE_PIGLIN) {
			return new TranslatedItemData(ItemIds::MOB_HEAD, TileSkull::TYPE_PLAYER, $this->getName());
		}

		return null;
	}
}
