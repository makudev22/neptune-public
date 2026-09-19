<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\CopperMaterial;
use pocketmine\block\utils\CopperOxidation;
use pocketmine\item\TieredTool;

class CopperGrate extends Solid implements CopperMaterial
{
	public function getOxidation() : int{
		return match ($this->id) {
			BlockIds::WAXED_OXIDIZED_COPPER_GRATE, BlockIds::OXIDIZED_COPPER_GRATE => CopperOxidation::OXIDIZED,
			BlockIds::WAXED_WEATHERED_COPPER_GRATE, BlockIds::WEATHERED_COPPER_GRATE => CopperOxidation::WEATHERED,
			BlockIds::WAXED_EXPOSED_COPPER_GRATE, BlockIds::EXPOSED_COPPER_GRATE => CopperOxidation::EXPOSED,
			default => CopperOxidation::NONE
		};
	}

	public function getPreviousOxidationId() : ?int
	{
		return match ($this->id) {
			BlockIds::OXIDIZED_COPPER_GRATE => BlockIds::WEATHERED_COPPER_GRATE,
			BlockIds::WEATHERED_COPPER_GRATE => BlockIds::EXPOSED_COPPER_GRATE,
			BlockIds::EXPOSED_COPPER_GRATE => BlockIds::COPPER_GRATE,
			default => null
		};
	}

	public function isWaxed() : bool
	{
		return match ($this->id) {
			BlockIds::WAXED_COPPER_GRATE, BlockIds::WAXED_EXPOSED_COPPER_GRATE, BlockIds::WAXED_WEATHERED_COPPER_GRATE, BlockIds::WAXED_OXIDIZED_COPPER_GRATE => true,
			default => false
		};
	}

	public function getWaxedId() : int{
		return match ($this->id) {
			BlockIds::OXIDIZED_COPPER_GRATE => BlockIds::WAXED_OXIDIZED_COPPER_GRATE,
			BlockIds::WEATHERED_COPPER_GRATE => BlockIds::WAXED_WEATHERED_COPPER_GRATE,
			BlockIds::EXPOSED_COPPER_GRATE => BlockIds::WAXED_EXPOSED_COPPER_GRATE,
			default => BlockIds::WAXED_COPPER_GRATE
		};
	}

	public function getNonWaxedId() : int{
		return match ($this->id) {
			BlockIds::WAXED_OXIDIZED_COPPER_GRATE => BlockIds::OXIDIZED_COPPER_GRATE,
			BlockIds::WAXED_WEATHERED_COPPER_GRATE => BlockIds::WEATHERED_COPPER_GRATE,
			BlockIds::WAXED_EXPOSED_COPPER_GRATE => BlockIds::EXPOSED_COPPER_GRATE,
			default => BlockIds::COPPER_GRATE
		};
	}

	public function getHardness() : float
	{
		return 3;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_STONE;
	}
}
