<?php


declare(strict_types=1);

namespace pocketmine\block;

class QuartzBricks extends Quartz
{
	protected $id = self::QUARTZ_BRICKS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Quartz Bricks";
	}

	public function getVariantBitmask() : int
	{
		return 0;
	}
}
