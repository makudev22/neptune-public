<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\CopperMaterial;
use pocketmine\block\utils\CopperOxidation;
use pocketmine\item\TieredTool;

class CopperBlock extends Solid implements CopperMaterial
{
	public function getOxidation() : int{
		return match ($this->id) {
			BlockIds::WAXED_OXIDIZED_COPPER, BlockIds::OXIDIZED_COPPER => CopperOxidation::OXIDIZED,
			BlockIds::WAXED_WEATHERED_COPPER, BlockIds::WEATHERED_COPPER => CopperOxidation::WEATHERED,
			BlockIds::WAXED_EXPOSED_COPPER, BlockIds::EXPOSED_COPPER => CopperOxidation::EXPOSED,
			default => CopperOxidation::NONE
		};
	}

	public function getPreviousOxidationId() : ?int
	{
		return match ($this->id) {
			BlockIds::OXIDIZED_COPPER => BlockIds::WEATHERED_COPPER,
			BlockIds::WEATHERED_COPPER => BlockIds::EXPOSED_COPPER,
			BlockIds::EXPOSED_COPPER => BlockIds::COPPER_BLOCK,
			default => null
		};
	}

	public function isWaxed() : bool
	{
		return match ($this->id) {
			BlockIds::WAXED_COPPER, BlockIds::WAXED_EXPOSED_COPPER, BlockIds::WAXED_WEATHERED_COPPER, BlockIds::WAXED_OXIDIZED_COPPER => true,
			default => false
		};
	}

	public function getWaxedId() : int{
		return match ($this->id) {
			BlockIds::OXIDIZED_COPPER => BlockIds::WAXED_OXIDIZED_COPPER,
			BlockIds::WEATHERED_COPPER => BlockIds::WAXED_WEATHERED_COPPER,
			BlockIds::EXPOSED_COPPER => BlockIds::WAXED_EXPOSED_COPPER,
			default => BlockIds::WAXED_COPPER
		};
	}

	public function getNonWaxedId() : int{
		return match ($this->id) {
			BlockIds::WAXED_OXIDIZED_COPPER => BlockIds::OXIDIZED_COPPER,
			BlockIds::WAXED_WEATHERED_COPPER => BlockIds::WEATHERED_COPPER,
			BlockIds::WAXED_EXPOSED_COPPER => BlockIds::EXPOSED_COPPER,
			default => BlockIds::COPPER_BLOCK
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
