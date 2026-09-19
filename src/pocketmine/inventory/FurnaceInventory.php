<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;
use pocketmine\tile\Furnace;

class FurnaceInventory extends ContainerInventory
{
	/** @var Furnace */
	protected $holder;

	public function __construct(Furnace $tile)
	{
		parent::__construct($tile);
	}

	public function getNetworkType() : int
	{
		return match($this->holder->getFurnaceType()){
			FurnaceType::BLAST_FURNACE => WindowTypes::BLAST_FURNACE,
			FurnaceType::SMOKER => WindowTypes::SMOKER,
			default => WindowTypes::FURNACE
		};
	}

	public function getName() : string
	{
		return "Furnace";
	}

	public function getDefaultSize() : int
	{
		return 3; //1 input, 1 fuel, 1 output
	}

	/**
	 * This override is here for documentation and code completion purposes only.
	 * @return Furnace
	 */
	public function getHolder()
	{
		return $this->holder;
	}

	public function getResult() : Item
	{
		return $this->getItem(2);
	}

	public function getFuel() : Item
	{
		return $this->getItem(1);
	}

	public function getSmelting() : Item
	{
		return $this->getItem(0);
	}

	public function setResult(Item $item) : bool
	{
		return $this->setItem(2, $item);
	}

	public function setFuel(Item $item) : bool
	{
		return $this->setItem(1, $item);
	}

	public function setSmelting(Item $item) : bool
	{
		return $this->setItem(0, $item);
	}
}
