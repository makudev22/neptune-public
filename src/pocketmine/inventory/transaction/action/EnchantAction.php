<?php


declare(strict_types=1);

namespace pocketmine\inventory\transaction\action;

use pocketmine\Player;

class EnchantAction extends SlotChangeAction
{
	public function isValid(Player $source) : bool
	{
		return true; // client-side enchant so we need this
	}
}
