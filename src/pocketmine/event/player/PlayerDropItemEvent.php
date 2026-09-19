<?php


declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\item\Item;
use pocketmine\Player;

/**
 * Called when a player tries to drop an item from its hotbar
 */
class PlayerDropItemEvent extends PlayerEvent implements Cancellable
{
	/** @var Item */
	private $drop;

	public function __construct(Player $player, Item $drop)
	{
		$this->player = $player;
		$this->drop = $drop;
	}

	public function getItem() : Item
	{
		return $this->drop;
	}
}
