<?php


declare(strict_types=1);

namespace pocketmine\block;

class WarpedStairs extends WoodenStairs
{
	protected $id = self::WARPED_STAIRS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Warped Stairs";
	}
}
