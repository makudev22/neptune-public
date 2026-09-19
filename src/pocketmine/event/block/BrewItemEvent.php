<?php


declare(strict_types=1);

namespace pocketmine\event\block;

use pocketmine\event\Cancellable;
use pocketmine\inventory\BrewingRecipe;
use pocketmine\item\Item;
use pocketmine\tile\BrewingStand;

class BrewItemEvent extends BlockEvent implements Cancellable
{
	public function __construct(
		private BrewingStand $brewingStand,
		private int $slot,
		private Item $input,
		private Item $result,
		private BrewingRecipe $recipe
	) {
		parent::__construct($brewingStand->getBlock());
	}

	public function getBrewingStand() : BrewingStand
	{
		return $this->brewingStand;
	}

	/**
	 * Returns which slot of the brewing stand's inventory the potion is in.
	 */
	public function getSlot() : int
	{
		return $this->slot;
	}

	public function getInput() : Item
	{
		return clone $this->input;
	}

	public function getResult() : Item
	{
		return clone $this->result;
	}

	public function setResult(Item $result) : void
	{
		$this->result = clone $result;
	}

	public function getRecipe() : BrewingRecipe
	{
		return $this->recipe;
	}
}
