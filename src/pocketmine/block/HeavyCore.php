<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\math\AxisAlignedBB;

class HeavyCore extends Flowable
{
	protected $id = self::HEAVY_CORE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getHardness() : float
	{
		return 10;
	}

	public function getName() : string
	{
		return "Heavy Core";
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function isSolid() : bool
	{
		return false;
	}

	protected function recalculateBoundingBox() : ?AxisAlignedBB
	{
		return new AxisAlignedBB(
			$this->x + 0.25,
			$this->y,
			$this->z + 0.25,
			$this->x + 0.75,
			$this->y + 0.5,
			$this->z + 0.75
		);
	}
}
