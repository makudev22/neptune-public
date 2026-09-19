<?php


declare(strict_types=1);

namespace pocketmine\block;

class StoneSlab4 extends StoneSlab
{
	public const TYPE_MOSSY_STONE_BRICK = 0;
	public const TYPE_SMOOTH_QUARTZ = 1;
	public const TYPE_STONE = 2;
	public const TYPE_CUT_SANDSTONE = 3;
	public const TYPE_CUT_RED_SANDSTONE = 4;

	protected $id = self::STONE_SLAB4;

	public function getDoubleSlabId() : int
	{
		return self::DOUBLE_STONE_SLAB4;
	}

	public function getName() : string
	{
		static $names = [
			self::TYPE_MOSSY_STONE_BRICK => "Mossy Stone",
			self::TYPE_SMOOTH_QUARTZ => "Smooth Quartz",
			self::TYPE_STONE => "Stone",
			self::TYPE_CUT_SANDSTONE => "Cut Sandstone",
			self::TYPE_CUT_RED_SANDSTONE => "Cut Red Sandstone",
		];

		return ($this->isTop() ? "Upper " : "") . ($names[$this->getVariant()] ?? "") . " Slab";
	}
}
