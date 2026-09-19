<?php


declare(strict_types=1);

namespace pocketmine\block;

class ChiseledNetherBricks extends NetherBrick
{
	protected $id = self::CHISELED_NETHER_BRICKS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Chiseled Nether Bricks";
	}
}
