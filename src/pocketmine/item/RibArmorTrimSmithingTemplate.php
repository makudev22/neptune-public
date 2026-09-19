<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\network\mcpe\protocol\ProtocolInfo;

class RibArmorTrimSmithingTemplate extends Item implements ArmorTrimSmithingTemplate {
	public function __construct(int $meta = 0){
		parent::__construct(self::RIB_ARMOR_TRIM_SMITHING_TEMPLATE, $meta, "Rib Armor Trim Smithing Template");
	}

	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData{
		if ($playerProtocol < ProtocolInfo::PROTOCOL_589) {
			return new TranslatedItemData(ItemIds::PAPER, 0);
		}

		return null;
	}
}
