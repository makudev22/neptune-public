<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;
use pocketmine\tile\Hopper;

class HopperInventory extends ContainerInventory
{
	/** @var Hopper */
	protected $holder;

	public function __construct(Hopper $tile)
	{
		parent::__construct($tile);
	}

	public function getNetworkType() : int
	{
		return WindowTypes::HOPPER;
	}

	public function getName() : string
	{
		return "Hopper";
	}

	public function getDefaultSize() : int
	{
		return 5;
	}

	/**
	 * This override is here for documentation and code completion purposes only.
	 * @return Hopper
	 */
	public function getHolder()
	{
		return $this->holder;
	}
}
