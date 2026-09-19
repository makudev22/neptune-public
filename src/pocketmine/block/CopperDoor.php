<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\CopperMaterial;
use pocketmine\block\utils\CopperOxidation;
use pocketmine\item\TieredTool;

class CopperDoor extends Door implements CopperMaterial
{

	public function getOxidation() : int{
		return match ($this->id) {
			BlockIds::WAXED_OXIDIZED_COPPER_DOOR, BlockIds::OXIDIZED_COPPER_DOOR => CopperOxidation::OXIDIZED,
			BlockIds::WAXED_WEATHERED_COPPER_DOOR, BlockIds::WEATHERED_COPPER_DOOR => CopperOxidation::WEATHERED,
			BlockIds::WAXED_EXPOSED_COPPER_DOOR, BlockIds::EXPOSED_COPPER_DOOR => CopperOxidation::EXPOSED,
			default => CopperOxidation::NONE
		};
	}

	public function getPreviousOxidationId() : ?int
	{
		return match ($this->id) {
			BlockIds::OXIDIZED_COPPER_DOOR => BlockIds::WEATHERED_COPPER_DOOR,
			BlockIds::WEATHERED_COPPER_DOOR => BlockIds::EXPOSED_COPPER_DOOR,
			BlockIds::EXPOSED_COPPER_DOOR => BlockIds::COPPER_DOOR,
			default => null
		};
	}

	public function isWaxed() : bool
	{
		return match ($this->id) {
			BlockIds::WAXED_COPPER_DOOR, BlockIds::WAXED_EXPOSED_COPPER_DOOR, BlockIds::WAXED_WEATHERED_COPPER_DOOR, BlockIds::WAXED_OXIDIZED_COPPER_DOOR => true,
			default => false
		};
	}

	public function getWaxedId() : int{
		return match ($this->id) {
			BlockIds::OXIDIZED_COPPER_DOOR => BlockIds::WAXED_OXIDIZED_COPPER_DOOR,
			BlockIds::WEATHERED_COPPER_DOOR => BlockIds::WAXED_WEATHERED_COPPER_DOOR,
			BlockIds::EXPOSED_COPPER_DOOR => BlockIds::WAXED_EXPOSED_COPPER_DOOR,
			default => BlockIds::WAXED_COPPER_DOOR
		};
	}

	public function getNonWaxedId() : int{
		return match ($this->id) {
			BlockIds::WAXED_OXIDIZED_COPPER_DOOR => BlockIds::OXIDIZED_COPPER_DOOR,
			BlockIds::WAXED_WEATHERED_COPPER_DOOR => BlockIds::WEATHERED_COPPER_DOOR,
			BlockIds::WAXED_EXPOSED_COPPER_DOOR => BlockIds::EXPOSED_COPPER_DOOR,
			default => BlockIds::COPPER_DOOR
		};
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_STONE;
	}

	public function getHardness() : float
	{
		return 3;
	}
}
