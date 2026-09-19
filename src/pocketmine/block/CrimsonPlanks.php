<?php


declare(strict_types=1);

namespace pocketmine\block;

class CrimsonPlanks extends Solid
{
	protected $id = self::CRIMSON_PLANKS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getHardness() : float
	{
		return 2;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_AXE;
	}

	public function getName() : string
	{
		return "Crimson Planks";
	}
}
