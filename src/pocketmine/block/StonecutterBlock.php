<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\inventory\StonecutterInventory;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;

class StonecutterBlock extends Stonecutter
{
	protected $id = self::STONECUTTER_BLOCK;

	public function onActivate(Item $item, Player $player = null) : bool
	{
		if ($player instanceof Player && $player->getProtocolVersion() >= ProtocolInfo::PROTOCOL_407) {
			$player->addWindow(new StonecutterInventory($this));
		}

		return true;
	}
}
