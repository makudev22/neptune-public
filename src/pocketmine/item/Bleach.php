<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\network\mcpe\protocol\ProtocolInfo;

class Bleach extends Item
{
	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData
	{
		if ($playerProtocol < ProtocolInfo::PROTOCOL_407) {
			return new TranslatedItemData(Item::POTION, $this->getDamage(), $this->getName());
		}

		return parent::getItemProtocol($playerProtocol);
	}

}
