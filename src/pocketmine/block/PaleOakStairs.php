<?php


declare(strict_types=1);

namespace pocketmine\block;

class PaleOakStairs extends WoodenStairs
{
	protected $id = self::PALE_OAK_STAIRS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Pale Oak Stairs";
	}
}
