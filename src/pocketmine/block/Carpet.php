<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\ColorBlockMetaHelper;
use pocketmine\block\utils\StaticSupportTrait;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;

class Carpet extends Flowable
{
	use StaticSupportTrait;

	protected $id = self::CARPET;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getHardness() : float
	{
		return 0.1;
	}

	public function isSolid() : bool
	{
		return true;
	}

	public function getName() : string
	{
		return ColorBlockMetaHelper::getColorFromMeta($this->getVariant()) . " Carpet";
	}

	protected function recalculateBoundingBox() : ?AxisAlignedBB
	{
		return new AxisAlignedBB(
			$this->x,
			$this->y,
			$this->z,
			$this->x + 1,
			$this->y + 0.0625,
			$this->z + 1
		);
	}

	protected function canBeSupportedAt(Block $block) : bool{
		return $block->getSide(Facing::DOWN)->getId() !== BlockIds::AIR;
	}

	public function getFlameEncouragement() : int{
		return 30;
	}

	public function getFlammability() : int{
		return 20;
	}
}
