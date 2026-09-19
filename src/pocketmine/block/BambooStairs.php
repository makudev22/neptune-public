<?php


declare(strict_types=1);

namespace pocketmine\block;

class BambooStairs extends WoodenStairs
{
	protected $id = self::BAMBOO_STAIRS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Bamboo Stairs";
	}
}
