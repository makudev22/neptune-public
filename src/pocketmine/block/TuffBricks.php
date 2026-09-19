<?php


declare(strict_types=1);

namespace pocketmine\block;

class TuffBricks extends Tuff
{
	protected $id = self::TUFF_BRICKS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Tuff Bricks";
	}
}
