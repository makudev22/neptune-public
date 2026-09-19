<?php


declare(strict_types=1);

namespace pocketmine\block;

class CherryButton extends WoodenButton
{
	protected $id = self::CHERRY_BUTTON;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Cherry Button";
	}
}
