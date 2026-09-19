<?php


declare(strict_types=1);

namespace pocketmine\block;

class Resin extends Solid
{
	protected $id = self::RESIN_BLOCK;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getHardness() : float
	{
		return 0;
	}

	public function getName() : string
	{
		return "Resin Block";
	}
}
