<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\StaticSupportTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\math\Axis;
use pocketmine\math\Facing;
use function mt_rand;

class ChorusPlant extends Flowable
{
	use StaticSupportTrait;

	protected $id = self::CHORUS_PLANT;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Chorus Plant";
	}

	public function getHardness() : float
	{
		return 0.4;
	}

	public function getBlastResistance() : float
	{
		return 0.4;
	}

	public function getVariantBitmask() : int{
		return 0;
	}

	protected function canBeSupportedBy(Block $block) : bool{
		return $block->isSameType($this) || $block->getId() === BlockIds::END_STONE;
	}

	protected function canBeSupportedAt(Block $block) : bool{
		$world = $block->getLevel();

		$down = $world->getBlock($block->down());
		$verticalAir = $down->getId() === BlockIds::AIR || $world->getBlock($block->up())->getId() === BlockIds::AIR;

		foreach($block->sidesAroundAxis(Axis::Y) as $sidePosition){
			$block = $world->getBlock($sidePosition);

			if($block->getId() === BlockIds::CHORUS_PLANT){
				if(!$verticalAir){
					return false;
				}

				if($this->canBeSupportedBy($block->getSide(Facing::DOWN))){
					return true;
				}
			}
		}

		return $this->canBeSupportedBy($down);
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		if(mt_rand(0, 1) === 1){
			return [ItemFactory::get(ItemIds::CHORUS_FRUIT)];
		}

		return [];
	}
}
