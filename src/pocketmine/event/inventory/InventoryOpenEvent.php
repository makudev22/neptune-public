<?php


declare(strict_types=1);

namespace pocketmine\event\inventory;

use pocketmine\event\Cancellable;
use pocketmine\inventory\Inventory;
use pocketmine\Player;

class InventoryOpenEvent extends InventoryEvent implements Cancellable
{
	/** @var Player */
	private $who;

	public function __construct(Inventory $inventory, Player $who)
	{
		$this->who = $who;
		parent::__construct($inventory);
	}

	public function getPlayer() : Player
	{
		return $this->who;
	}
}
