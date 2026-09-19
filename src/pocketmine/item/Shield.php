<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;

class Shield extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::SHIELD, $meta, "Shield");
	}

	public function onUpdate(Player $player) : void
	{
		$player->setGenericFlag(Entity::DATA_FLAG_BLOCKING, $player->isSneaking());
	}

	public function getMaxStackSize() : int
	{
		return 1;
	}

	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData
	{
		if ($playerProtocol < ProtocolInfo::PROTOCOL_407) {
			return new TranslatedItemData(ItemIds::PAPER, $this->getDamage(), $this->getName());
		}

		return parent::getItemProtocol($playerProtocol);
	}
}
