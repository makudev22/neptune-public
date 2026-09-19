<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class NetheriteBlock extends Solid
{
	protected $id = self::NETHERITE_BLOCK;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Netherite Block";
	}

	public function getHardness() : float
	{
		// TODO Should be 50, but the break time is glitchy (same with obsidian but less noticeable because of the texture)
		return 35;
	}

	public function getBlastResistance() : float
	{
		return 6000;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_DIAMOND;
	}
}
