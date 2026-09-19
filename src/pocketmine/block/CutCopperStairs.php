<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\CopperMaterial;
use pocketmine\block\utils\CopperOxidation;
use pocketmine\item\TieredTool;

class CutCopperStairs extends Stair implements CopperMaterial
{

	public function getOxidation() : int{
		return match ($this->id) {
			BlockIds::WAXED_OXIDIZED_CUT_COPPER_STAIRS, BlockIds::OXIDIZED_CUT_COPPER_STAIRS => CopperOxidation::OXIDIZED,
			BlockIds::WAXED_WEATHERED_CUT_COPPER_STAIRS, BlockIds::WEATHERED_CUT_COPPER_STAIRS => CopperOxidation::WEATHERED,
			BlockIds::WAXED_EXPOSED_CUT_COPPER_STAIRS, BlockIds::EXPOSED_CUT_COPPER_STAIRS => CopperOxidation::EXPOSED,
			default => CopperOxidation::NONE
		};
	}

	public function getPreviousOxidationId() : ?int
	{
		return match ($this->id) {
			BlockIds::OXIDIZED_CUT_COPPER_STAIRS => BlockIds::WEATHERED_CUT_COPPER_STAIRS,
			BlockIds::WEATHERED_CUT_COPPER_STAIRS => BlockIds::EXPOSED_CUT_COPPER_STAIRS,
			BlockIds::EXPOSED_CUT_COPPER_STAIRS => BlockIds::CUT_COPPER_STAIRS,
			default => null
		};
	}

	public function isWaxed() : bool
	{
		return match ($this->id) {
			BlockIds::WAXED_CUT_COPPER_STAIRS, BlockIds::WAXED_EXPOSED_CUT_COPPER_STAIRS, BlockIds::WAXED_WEATHERED_CUT_COPPER_STAIRS, BlockIds::WAXED_OXIDIZED_CUT_COPPER_STAIRS => true,
			default => false
		};
	}

	public function getWaxedId() : int{
		return match ($this->id) {
			BlockIds::OXIDIZED_CUT_COPPER_STAIRS => BlockIds::WAXED_OXIDIZED_CUT_COPPER_STAIRS,
			BlockIds::WEATHERED_CUT_COPPER_STAIRS => BlockIds::WAXED_WEATHERED_CUT_COPPER_STAIRS,
			BlockIds::EXPOSED_CUT_COPPER_STAIRS => BlockIds::WAXED_EXPOSED_CUT_COPPER_STAIRS,
			default => BlockIds::WAXED_CUT_COPPER_STAIRS
		};
	}

	public function getNonWaxedId() : int{
		return match ($this->id) {
			BlockIds::WAXED_OXIDIZED_CUT_COPPER_STAIRS => BlockIds::OXIDIZED_CUT_COPPER_STAIRS,
			BlockIds::WAXED_WEATHERED_CUT_COPPER_STAIRS => BlockIds::WEATHERED_CUT_COPPER_STAIRS,
			BlockIds::WAXED_EXPOSED_CUT_COPPER_STAIRS => BlockIds::EXPOSED_CUT_COPPER_STAIRS,
			default => BlockIds::CUT_COPPER_STAIRS
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
