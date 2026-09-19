<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;

class SuspiciousSand extends Fallable
{
	protected $id = self::SUSPICIOUS_SAND;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Suspicious Sand";
	}

	public function getHardness() : float
	{
		return 0.25;
	}

	public function getBlastResistance() : float{
		return 0.25;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_SHOVEL;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [];
	}
}
