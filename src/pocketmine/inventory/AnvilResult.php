<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\item\Item;

class AnvilResult {

	public function __construct(
		private Item $output,
		private int  $xpCost
	){}

	public function getOutput() : Item{
		return $this->output;
	}

	public function getXpCost() : int{
		return $this->xpCost;
	}
}
