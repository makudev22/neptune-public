<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;

class Spyglass extends Item implements Releasable
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::SPYGLASS, $meta, "Spyglass");
	}

	public function getMaxStackSize() : int
	{
		return 1;
	}

	public function canStartUsingItem(Player $player) : bool
	{
		return true;
	}

	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData
	{
		if ($playerProtocol < ProtocolInfo::PROTOCOL_440) {
			return new TranslatedItemData(ItemIds::BLAZE_ROD, $this->getDamage(), $this->getName());
		}

		return parent::getItemProtocol($playerProtocol);
	}
}
