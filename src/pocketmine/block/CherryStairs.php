<?php


declare(strict_types=1);

namespace pocketmine\block;

class CherryStairs extends WoodenStairs
{
	protected $id = self::CHERRY_STAIRS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Cherry Stairs";
	}
}
