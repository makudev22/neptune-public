<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\types\DimensionIds;

class Bedrock extends Solid
{
	protected $id = self::BEDROCK;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Bedrock";
	}

	public function getHardness() : float
	{
		return -1;
	}

	public function getBlastResistance() : float
	{
		return 18000000;
	}

	public function isBreakable(Item $item) : bool
	{
		return false;
	}

	public function burnsForever() : bool{
		return $this->level->getDimension() === DimensionIds::THE_END;
	}

	public function getBlock() : Block
	{
		return BlockFactory::get(Block::BED_BLOCK);
	}
}
