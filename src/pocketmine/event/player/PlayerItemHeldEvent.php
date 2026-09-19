<?php


declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\item\Item;
use pocketmine\Player;

class PlayerItemHeldEvent extends PlayerEvent implements Cancellable
{
	/** @var Item */
	private $item;
	/** @var int */
	private $hotbarSlot;

	public function __construct(Player $player, Item $item, int $hotbarSlot)
	{
		$this->player = $player;
		$this->item = $item;
		$this->hotbarSlot = $hotbarSlot;
	}

	/**
	 * Returns the hotbar slot the player is attempting to hold.
	 */
	public function getSlot() : int
	{
		return $this->hotbarSlot;
	}

	public function getItem() : Item
	{
		return $this->item;
	}
}
