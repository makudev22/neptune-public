<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;
use pocketmine\tile\Campfire;

class CampfireInventory extends ContainerInventory
{
	/** @var Campfire */
	protected $holder;

	public function __construct(Campfire $tile)
	{
		parent::__construct($tile);
	}

	public function getNetworkType() : int
	{
		return WindowTypes::NONE;
	}

	public function getName() : string
	{
		return "Campfire";
	}

	public function getDefaultSize() : int
	{
		return 4;
	}

	/**
	 * @return Campfire
	 */
	public function getHolder()
	{
		return $this->holder;
	}
}
