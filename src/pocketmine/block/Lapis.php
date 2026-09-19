<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class Lapis extends Solid
{
	protected $id = self::LAPIS_BLOCK;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Lapis Lazuli Block";
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
