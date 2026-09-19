<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\BlockEventHelper;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\math\Facing;

use function mt_rand;

class Mycelium extends Solid
{
	protected $id = self::MYCELIUM;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Mycelium";
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_SHOVEL;
	}

	public function getHardness() : float
	{
		return 0.6;
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [
			ItemFactory::get(ItemIds::DIRT)
		];
	}

	public function isAffectedBySilkTouch() : bool{
		return true;
	}

	public function ticksRandomly() : bool{
		return true;
	}

	public function onRandomTick() : void{
		//TODO: light levels
		$x = mt_rand($this->x - 1, $this->x + 1);
		$y = mt_rand($this->y - 2, $this->y + 2);
		$z = mt_rand($this->z - 1, $this->z + 1);
		$level = $this->level;
		$block = $level->getBlockAt($x, $y, $z);
		if($block instanceof Dirt && $block->getDamage() === Dirt::TYPE_NORMAL){
			if($block->getSide(Facing::UP) instanceof Transparent){
				BlockEventHelper::spread($block, BlockFactory::get(BlockIds::MYCELIUM), $this);
			}
		}
	}
}
