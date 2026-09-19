<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\network\mcpe\protocol\ProtocolInfo;

class PaleOakSign extends Sign
{
	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData
	{
		if ($playerProtocol < ProtocolInfo::PROTOCOL_766) {
			return new TranslatedItemData(ItemIds::SIGN, $this->getDamage(), $this->getName());
		}

		return parent::getItemProtocol($playerProtocol);
	}
}
