<?php


declare(strict_types=1);

namespace pocketmine\block;

class DirtWithRoots extends Transparent
{
	protected $id = self::DIRT_WITH_ROOTS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Dirt With Roots";
	}

	public function getHardness() : float
	{
		return 0.5;
	}

	public function getBlastResistance() : float
	{
		return 0.1;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_SHOVEL;
	}
}
