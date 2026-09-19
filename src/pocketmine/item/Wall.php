<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

class Wall extends ItemBlock
{
	protected int $olderMetaBlockId;

	public function __construct(int $id, int $meta, Block $name, int $oldMetaBlockId){
		parent::__construct($id, $meta, $name);
		$this->olderMetaBlockId = $oldMetaBlockId;
	}

	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData
	{
		if ($playerProtocol < ProtocolInfo::PROTOCOL_407) {
			return new TranslatedItemData(ItemIds::COBBLESTONE_WALL, ($this->olderMetaBlockId % 2), $this->getName());
		}elseif ($playerProtocol < ProtocolInfo::PROTOCOL_729) {
			return new TranslatedItemData(ItemIds::COBBLESTONE_WALL, $this->olderMetaBlockId, $this->getName());
		}

		return null;
	}
}
