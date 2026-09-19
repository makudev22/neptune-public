<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\network\mcpe\protocol\ProtocolInfo;

class NetheriteUpgradeSmithingTemplate extends Item {
	public function __construct(int $meta = 0){
		parent::__construct(self::NETHERITE_UPGRADE_SMITHING_TEMPLATE, $meta, "Netherite Upgrade Smithing Template");
	}

	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData{
		if ($playerProtocol < ProtocolInfo::PROTOCOL_589) {
			return new TranslatedItemData(ItemIds::PAPER, 0);
		}

		return null;
	}
}
