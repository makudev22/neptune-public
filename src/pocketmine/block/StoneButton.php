<?php


declare(strict_types=1);

namespace pocketmine\block;

class StoneButton extends Button
{
	protected $id = self::STONE_BUTTON;

	public function getName() : string
	{
		return "Stone Button";
	}

	public function getHardness() : float
	{
		return 0.5;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	protected function getActivationTime() : int
	{
		return 20;
	}
}
