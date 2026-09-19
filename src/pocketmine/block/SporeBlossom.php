<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\StaticSupportTrait;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;

class SporeBlossom extends Flowable
{
	use StaticSupportTrait;

	protected $id = self::SPORE_BLOSSOM;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Spore Blossom";
	}

	protected function recalculateBoundingBox() : ?AxisAlignedBB
	{
		return new AxisAlignedBB(
			$this->x + 0.125,
			$this->y + 0.8125,
			$this->z + 0.125,
			$this->x + 0.875,
			$this->y + 1,
			$this->z + 0.875,
		);
	}

	protected function canBeSupportedAt(Block $block) : bool{
		return $this->canSupportToFullSolid($this->getSide(Facing::UP));
	}
}
