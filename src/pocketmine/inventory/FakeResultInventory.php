<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\Player;

interface FakeResultInventory
{

	public function onResult(Player $player, Item $result) : bool;
}
