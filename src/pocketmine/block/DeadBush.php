<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\BushTrait;
use pocketmine\block\utils\StaticSupportTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\math\Facing;
use function mt_rand;

class DeadBush extends Flowable {
	use BushTrait;
	use StaticSupportTrait;

	protected $id = self::DEAD_BUSH;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Dead Bush";
	}

	public function canBeReplaced() : bool{
		return false;
	}

	public function getDropsForIncompatibleTool(Item $item) : array
	{
		return [
			ItemFactory::get(ItemIds::STICK)->setCount(mt_rand(0, 2))
		];
	}

	public function isAffectedBySilkTouch() : bool
	{
		return true;
	}

	protected function canBeSupportedAt(Block $block) : bool{
		$supportBlock = $block->getSide(Facing::DOWN);
		return
			$supportBlock instanceof Sand ||
			$supportBlock instanceof Mud ||
			match($supportBlock->getId()){
				//can't use DIRT tag here because it includes farmland
				BlockIds::PODZOL,
				BlockIds::MYCELIUM,
				BlockIds::DIRT,
				BlockIds::GRASS,
				BlockIds::HARDENED_CLAY,
				BlockIds::MOSS_BLOCK,
				BlockIds::STAINED_CLAY => true,
				default => false,
			};
	}
}
