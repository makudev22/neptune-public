<?php


declare(strict_types=1);

namespace pocketmine\block;

class WarpedButton extends WoodenButton
{
	protected $id = self::WARPED_BUTTON;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Warped Button";
	}
}
