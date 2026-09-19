<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\CopperMaterial;
use pocketmine\block\utils\CopperOxidation;
use pocketmine\item\TieredTool;

class CopperTrapdoor extends Trapdoor implements CopperMaterial
{

	public function getOxidation() : int{
		return match ($this->id) {
			BlockIds::WAXED_OXIDIZED_COPPER_TRAPDOOR, BlockIds::OXIDIZED_COPPER_TRAPDOOR => CopperOxidation::OXIDIZED,
			BlockIds::WAXED_WEATHERED_COPPER_TRAPDOOR, BlockIds::WEATHERED_COPPER_TRAPDOOR => CopperOxidation::WEATHERED,
			BlockIds::WAXED_EXPOSED_COPPER_TRAPDOOR, BlockIds::EXPOSED_COPPER_TRAPDOOR => CopperOxidation::EXPOSED,
			default => CopperOxidation::NONE
		};
	}

	public function getPreviousOxidationId() : ?int
	{
		return match ($this->id) {
			BlockIds::OXIDIZED_COPPER_TRAPDOOR => BlockIds::WEATHERED_COPPER_TRAPDOOR,
			BlockIds::WEATHERED_COPPER_TRAPDOOR => BlockIds::EXPOSED_COPPER_TRAPDOOR,
			BlockIds::EXPOSED_COPPER_TRAPDOOR => BlockIds::COPPER_TRAPDOOR,
			default => null
		};
	}

	public function isWaxed() : bool
	{
		return match ($this->id) {
			BlockIds::WAXED_COPPER_TRAPDOOR, BlockIds::WAXED_EXPOSED_COPPER_TRAPDOOR, BlockIds::WAXED_WEATHERED_COPPER_TRAPDOOR, BlockIds::WAXED_OXIDIZED_COPPER_TRAPDOOR => true,
			default => false
		};
	}

	public function getWaxedId() : int{
		return match ($this->id) {
			BlockIds::OXIDIZED_COPPER_TRAPDOOR => BlockIds::WAXED_OXIDIZED_COPPER_TRAPDOOR,
			BlockIds::WEATHERED_COPPER_TRAPDOOR => BlockIds::WAXED_WEATHERED_COPPER_TRAPDOOR,
			BlockIds::EXPOSED_COPPER_TRAPDOOR => BlockIds::WAXED_EXPOSED_COPPER_TRAPDOOR,
			default => BlockIds::WAXED_COPPER_TRAPDOOR
		};
	}

	public function getNonWaxedId() : int{
		return match ($this->id) {
			BlockIds::WAXED_OXIDIZED_COPPER_TRAPDOOR => BlockIds::OXIDIZED_COPPER_TRAPDOOR,
			BlockIds::WAXED_WEATHERED_COPPER_TRAPDOOR => BlockIds::WEATHERED_COPPER_TRAPDOOR,
			BlockIds::WAXED_EXPOSED_COPPER_TRAPDOOR => BlockIds::EXPOSED_COPPER_TRAPDOOR,
			default => BlockIds::COPPER_TRAPDOOR
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
