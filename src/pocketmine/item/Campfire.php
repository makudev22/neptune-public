<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\network\mcpe\protocol\ProtocolInfo;

class Campfire extends ItemBlock {

	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData{
		if ($playerProtocol < ProtocolInfo::PROTOCOL_354) {
			return new TranslatedItemData(ItemIds::FIRE_CHARGE, 0);
		}

		return null;
	}
}
