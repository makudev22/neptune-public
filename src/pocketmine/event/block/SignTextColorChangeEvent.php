<?php


declare(strict_types=1);

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\utils\Color;

class SignTextColorChangeEvent extends BlockEvent implements Cancellable
{
	private Color $color;

	public function __construct(Block $block, Color $color)
	{
		parent::__construct($block);

		$this->color = $color;
	}

	public function getColor() : Color
	{
		return $this->color;
	}

	public function setColor(Color $color) : void
	{
		$this->color = $color;
	}
}
