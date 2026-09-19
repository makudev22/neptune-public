<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\BlockEventHelper;
use pocketmine\block\utils\FortuneDropHelper;
use pocketmine\block\utils\StaticSupportTrait;
use pocketmine\item\Item;
use pocketmine\math\Facing;

use function mt_rand;

class NetherWartPlant extends Flowable
{
	use StaticSupportTrait;

	public const MAX_AGE = 3;

	protected $id = Block::NETHER_WART_PLANT;

	protected $itemId = Item::NETHER_WART;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Nether Wart";
	}

	protected function canBeSupportedAt(Block $block) : bool{
		return $block->getSide(Facing::DOWN)->getId() === BlockIds::SOUL_SAND;
	}

	public function ticksRandomly() : bool{
		return $this->meta < self::MAX_AGE;
	}

	public function onRandomTick() : void{
		if($this->meta < self::MAX_AGE && mt_rand(0, 10) === 0){ //Still growing
			$block = clone $this;
			$block->meta++;
			BlockEventHelper::grow($this, $block, null);
		}
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [
			$this->asItem()->setCount($this->meta === self::MAX_AGE ? FortuneDropHelper::discrete($item, 2, 4) : 1)
		];
	}
}
