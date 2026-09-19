<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;

class SuspiciousGravel extends Fallable
{
	protected $id = self::SUSPICIOUS_GRAVEL;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Suspicious Gravel";
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
