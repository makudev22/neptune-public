<?php


declare(strict_types=1);

namespace pocketmine\block;

class Barrier extends Transparent
{
	protected $id = self::BARRIER;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Barrier";
	}

	public function getHardness() : float
	{
		return -1.0;
	}

	public function getBlastResistance() : float
	{
		return 18000000.0;
	}
}
