<?php


declare(strict_types=1);

namespace pocketmine\block;

class Sponge extends Solid
{
	protected $id = self::SPONGE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_HOE;
	}

	public function getHardness() : float
	{
		return 0.6;
	}

	public function getName() : string
	{
		return "Sponge";
	}
}
