<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;

class GlassPane extends Thin
{
	protected $id = self::GLASS_PANE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Glass Pane";
	}

	public function getHardness() : float
	{
		return 0.3;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [];
	}
}
