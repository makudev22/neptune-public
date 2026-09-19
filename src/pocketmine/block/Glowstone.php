<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

use function mt_rand;

class Glowstone extends Transparent
{
	protected $id = self::GLOWSTONE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Glowstone";
	}

	public function getHardness() : float
	{
		return 0.3;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getLightLevel() : int
	{
		return 15;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [
			ItemFactory::get(Item::GLOWSTONE_DUST, 0, mt_rand(2, 4))
		];
	}
}
