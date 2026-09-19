<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

class SeaLantern extends Transparent
{
	protected $id = self::SEA_LANTERN;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Sea Lantern";
	}

	public function getHardness() : float
	{
		return 0.3;
	}

	public function getLightLevel() : int
	{
		return 15;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [
			ItemFactory::get(Item::PRISMARINE_CRYSTALS, 0, 3)
		];
	}

	public function isAffectedBySilkTouch() : bool
	{
		return true;
	}
}
