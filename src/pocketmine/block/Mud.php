<?php


declare(strict_types=1);

namespace pocketmine\block;

class Mud extends Solid
{
	protected $id = self::MUD;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Mud";
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_SHOVEL;
	}

	public function getHardness() : float
	{
		return 0.5;
	}
}
