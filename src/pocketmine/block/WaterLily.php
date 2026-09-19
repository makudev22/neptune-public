<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\StaticSupportTrait;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;

class WaterLily extends Flowable
{
	use StaticSupportTrait {
		canBePlacedAt as supportedWhenPlacedAt;
	}

	protected $id = self::WATER_LILY;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Lily Pad";
	}

	public function getHardness() : float
	{
		return 0.6;
	}

	protected function recalculateBoundingBox() : ?AxisAlignedBB
	{
		return new AxisAlignedBB(
			$this->x + 0.0625,
			$this->y,
			$this->z + 0.0625,
			$this->x + 0.9375,
			$this->y + 0.015625,
			$this->z + 0.9375
		);
	}

	public function canBePlacedAt(Block $blockReplace, Vector3 $clickVector, int $face, bool $isClickedBlock) : bool{
		return !$blockReplace instanceof Water && $this->supportedWhenPlacedAt($blockReplace, $clickVector, $face, $isClickedBlock);
	}

	protected function canBeSupportedAt(Block $block) : bool{
		return $block->getSide(Facing::DOWN) instanceof Water;
	}
}
