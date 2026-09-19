<?php


declare(strict_types=1);

namespace pocketmine\block;

class CrackedNetherBricks extends NetherBrick
{
	protected $id = self::CRACKED_NETHER_BRICKS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Cracked Nether Bricks";
	}
}
