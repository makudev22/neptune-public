<?php


declare(strict_types=1);

namespace pocketmine\block;

class PackedMud extends Solid
{
	protected $id = self::PACKED_MUD;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Packed Mud";
	}

	public function getHardness() : float
	{
		return 1;
	}

	public function getBlastResistance() : float
	{
		return 15;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}
}
