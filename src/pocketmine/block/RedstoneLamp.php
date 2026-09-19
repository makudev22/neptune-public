<?php


declare(strict_types=1);

namespace pocketmine\block;

class RedstoneLamp extends Solid
{
	protected $id = self::REDSTONE_LAMP;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Redstone Lamp";
	}

	public function getHardness() : float
	{
		return 0.3;
	}
}
