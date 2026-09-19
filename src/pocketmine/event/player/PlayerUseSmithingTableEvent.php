<?php


declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\item\Item;
use pocketmine\Player;

/**
 * Called when a player uses a smithing table (upgrading an item or applying an armor trim).
 */
class PlayerUseSmithingTableEvent extends PlayerEvent implements Cancellable{

	public function __construct(
		Player $player,
		private Item $inputItem,
		private Item $resultItem
	){
		$this->player = $player;
	}

	/**
	 * Returns the item being upgraded (the item placed in the smithing table's input slot).
	 */
	public function getInputItem() : Item{
		return $this->inputItem;
	}

	/**
	 * Returns the item that the player will receive as a result of the smithing operation.
	 */
	public function getResultItem() : Item{
		return $this->resultItem;
	}
}
