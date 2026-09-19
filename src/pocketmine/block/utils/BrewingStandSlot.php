<?php


declare(strict_types=1);

namespace pocketmine\block\utils;

use pocketmine\inventory\BrewingStandInventory;
use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static BrewingStandSlot EAST()
 * @method static BrewingStandSlot NORTHWEST()
 * @method static BrewingStandSlot SOUTHWEST()
 */
final class BrewingStandSlot
{
	use EnumTrait {
		__construct as Enum___construct;
	}

	protected static function setup() : void
	{
		self::registerAll(
			new self("east", BrewingStandInventory::SLOT_BOTTLE_LEFT),
			new self("northwest", BrewingStandInventory::SLOT_BOTTLE_MIDDLE),
			new self("southwest", BrewingStandInventory::SLOT_BOTTLE_RIGHT)
		);
	}

	private function __construct(string $enumName, private int $slotNumber)
	{
		$this->Enum___construct($enumName);
	}

	/**
	 * Returns the brewing stand inventory slot number associated with this visual slot.
	 */
	public function getSlotNumber() : int
	{
		return $this->slotNumber;
	}
}
