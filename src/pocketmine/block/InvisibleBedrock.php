<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;

class InvisibleBedrock extends Transparent
{
	protected $id = self::INVISIBLE_BEDROCK;

	public function __construct()
	{

	}

	public function getName() : string
	{
		return "Invisible Bedrock";
	}

	public function getHardness() : float
	{
		return -1;
	}

	public function getBlastResistance() : float
	{
		return 18000000;
	}

	public function isBreakable(Item $item) : bool
	{
		return false;
	}
}
