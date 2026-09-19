<?php


declare(strict_types=1);

namespace pocketmine\block;

class Podzol extends Solid
{
	protected $id = self::PODZOL;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_SHOVEL;
	}

	public function getName() : string
	{
		return "Podzol";
	}

	public function getHardness() : float
	{
		return 0.5;
	}
}
