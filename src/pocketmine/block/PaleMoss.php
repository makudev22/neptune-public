<?php


declare(strict_types=1);

namespace pocketmine\block;

class PaleMoss extends Solid
{
	protected $id = self::PALE_MOSS_BLOCK;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getHardness() : float
	{
		return 0.1;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_HOE;
	}

	public function getName() : string
	{
		return "Pale Moss Block";
	}
}
