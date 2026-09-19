<?php


declare(strict_types=1);

namespace pocketmine\block;

class RepeatingCommandBlock extends Solid
{
	protected $id = self::REPEATING_COMMAND_BLOCK;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Repeating Command Block";
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
