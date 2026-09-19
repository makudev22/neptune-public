<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;

class PackedIce extends Solid
{
	protected $id = self::PACKED_ICE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Packed Ice";
	}

	public function getHardness() : float
	{
		return 0.5;
	}

	public function getFrictionFactor() : float
	{
		return 0.98;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [];
	}
}
