<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\CopperMaterial;
use pocketmine\block\utils\CopperOxidation;
use pocketmine\item\TieredTool;

class CutCopperSlab extends Slab implements CopperMaterial
{
	protected int $doubleSlabId;

	public function __construct(int $id, int $meta, string $name, int $doubleSlabId)
	{
		$this->id = $id;
		$this->meta = $meta;
		$this->fallbackName = $name;
		$this->doubleSlabId = $doubleSlabId;
	}

	public function getDoubleSlabId() : int
	{
		return $this->doubleSlabId;
	}

	public function getVariantBitmask() : int
	{
		return 0x00;
	}

	public function getTopBitmask() : int
	{
		return 0x01;
	}

	public function getOxidation() : int{
		return match ($this->id) {
			BlockIds::WAXED_OXIDIZED_CUT_COPPER_SLAB, BlockIds::OXIDIZED_CUT_COPPER_SLAB => CopperOxidation::OXIDIZED,
			BlockIds::WAXED_WEATHERED_CUT_COPPER_SLAB, BlockIds::WEATHERED_CUT_COPPER_SLAB => CopperOxidation::WEATHERED,
			BlockIds::WAXED_EXPOSED_CUT_COPPER_SLAB, BlockIds::EXPOSED_CUT_COPPER_SLAB => CopperOxidation::EXPOSED,
			default => CopperOxidation::NONE
		};
	}

	public function getPreviousOxidationId() : ?int
	{
		return match ($this->id) {
			BlockIds::OXIDIZED_CUT_COPPER_SLAB => BlockIds::WEATHERED_CUT_COPPER_SLAB,
			BlockIds::WEATHERED_CUT_COPPER_SLAB => BlockIds::EXPOSED_CUT_COPPER_SLAB,
			BlockIds::EXPOSED_CUT_COPPER_SLAB => BlockIds::CUT_COPPER_SLAB,
			default => null
		};
	}

	public function isWaxed() : bool
	{
		return match ($this->id) {
			BlockIds::WAXED_CUT_COPPER_SLAB, BlockIds::WAXED_EXPOSED_CUT_COPPER_SLAB, BlockIds::WAXED_WEATHERED_CUT_COPPER_SLAB, BlockIds::WAXED_OXIDIZED_CUT_COPPER_SLAB => true,
			default => false
		};
	}

	public function getWaxedId() : int{
		return match ($this->id) {
			BlockIds::OXIDIZED_CUT_COPPER_SLAB => BlockIds::WAXED_OXIDIZED_CUT_COPPER_SLAB,
			BlockIds::WEATHERED_CUT_COPPER_SLAB => BlockIds::WAXED_WEATHERED_CUT_COPPER_SLAB,
			BlockIds::EXPOSED_CUT_COPPER_SLAB => BlockIds::WAXED_EXPOSED_CUT_COPPER_SLAB,
			default => BlockIds::WAXED_CUT_COPPER_SLAB
		};
	}

	public function getNonWaxedId() : int{
		return match ($this->id) {
			BlockIds::WAXED_OXIDIZED_CUT_COPPER_SLAB => BlockIds::OXIDIZED_CUT_COPPER_SLAB,
			BlockIds::WAXED_WEATHERED_CUT_COPPER_SLAB => BlockIds::WEATHERED_CUT_COPPER_SLAB,
			BlockIds::WAXED_EXPOSED_CUT_COPPER_SLAB => BlockIds::EXPOSED_CUT_COPPER_SLAB,
			default => BlockIds::CUT_COPPER_SLAB
		};
	}

	public function getName() : string
	{
		return ($this->isTop() ? "Upper " : "") . $this->fallbackName;
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
