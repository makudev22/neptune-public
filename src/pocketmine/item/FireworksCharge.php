<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\network\mcpe\protocol\ProtocolInfo;

class FireworksCharge extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::FIREWORKS_CHARGE, $meta, "Fireworks Charge");
	}

	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData
	{
		if ($playerProtocol < ProtocolInfo::PROTOCOL_407) {
			return new TranslatedItemData(ItemIds::NETHER_STAR, $this->getDamage(), $this->getName());
		}

		return parent::getItemProtocol($playerProtocol);
	}
}
