<?php


declare(strict_types=1);

namespace pocketmine\block;

class MangroveStairs extends WoodenStairs
{
	protected $id = self::MANGROVE_STAIRS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Mangrove Stairs";
	}
}
