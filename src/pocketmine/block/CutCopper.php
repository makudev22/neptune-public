<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\CopperMaterial;
use pocketmine\block\utils\CopperOxidation;
use pocketmine\item\TieredTool;

class CutCopper extends Solid implements CopperMaterial
{
	public function getOxidation() : int{
		return match ($this->id) {
			BlockIds::WAXED_OXIDIZED_CUT_COPPER, BlockIds::OXIDIZED_CUT_COPPER => CopperOxidation::OXIDIZED,
			BlockIds::WAXED_WEATHERED_CUT_COPPER, BlockIds::WEATHERED_CUT_COPPER => CopperOxidation::WEATHERED,
			BlockIds::WAXED_EXPOSED_CUT_COPPER, BlockIds::EXPOSED_CUT_COPPER => CopperOxidation::EXPOSED,
			default => CopperOxidation::NONE
		};
	}

	public function getPreviousOxidationId() : ?int
	{
		return match ($this->id) {
			BlockIds::OXIDIZED_CUT_COPPER => BlockIds::WEATHERED_CUT_COPPER,
			BlockIds::WEATHERED_CUT_COPPER => BlockIds::EXPOSED_CUT_COPPER,
			BlockIds::EXPOSED_CUT_COPPER => BlockIds::CUT_COPPER,
			default => null
		};
	}

	public function isWaxed() : bool
	{
		return match ($this->id) {
			BlockIds::WAXED_CUT_COPPER, BlockIds::WAXED_EXPOSED_CUT_COPPER, BlockIds::WAXED_WEATHERED_CUT_COPPER, BlockIds::WAXED_OXIDIZED_CUT_COPPER => true,
			default => false
		};
	}

	public function getWaxedId() : int{
		return match ($this->id) {
			BlockIds::OXIDIZED_CUT_COPPER => BlockIds::WAXED_OXIDIZED_CUT_COPPER,
			BlockIds::WEATHERED_CUT_COPPER => BlockIds::WAXED_WEATHERED_CUT_COPPER,
			BlockIds::EXPOSED_CUT_COPPER => BlockIds::WAXED_EXPOSED_CUT_COPPER,
			default => BlockIds::WAXED_CUT_COPPER
		};
	}

	public function getNonWaxedId() : int{
		return match ($this->id) {
			BlockIds::WAXED_OXIDIZED_CUT_COPPER => BlockIds::OXIDIZED_CUT_COPPER,
			BlockIds::WAXED_WEATHERED_CUT_COPPER => BlockIds::WEATHERED_CUT_COPPER,
			BlockIds::WAXED_EXPOSED_CUT_COPPER => BlockIds::EXPOSED_CUT_COPPER,
			default => BlockIds::CUT_COPPER
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
