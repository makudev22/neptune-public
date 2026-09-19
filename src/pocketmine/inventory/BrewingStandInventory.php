<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;
use pocketmine\tile\BrewingStand;

class BrewingStandInventory extends ContainerInventory
{
	public const SLOT_INGREDIENT = 0;
	public const SLOT_BOTTLE_LEFT = 1;
	public const SLOT_BOTTLE_MIDDLE = 2;
	public const SLOT_BOTTLE_RIGHT = 3;
	public const SLOT_FUEL = 4;

	/** @var BrewingStand */
	protected $holder;

	public function __construct(BrewingStand $holder, array $items = [], int $size = null, string $title = null)
	{
		parent::__construct($holder, $items, $size, $title);
	}

	public function getDefaultSize() : int
	{
		return 5;
	}

	public function getName() : string
	{
		return "Brewing";
	}

	public function getNetworkType() : int
	{
		return WindowTypes::BREWING_STAND;
	}
}
