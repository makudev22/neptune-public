<?php


declare(strict_types=1);

namespace pocketmine\block;

class MangroveButton extends WoodenButton
{
	protected $id = self::MANGROVE_BUTTON;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Mangrove Button";
	}
}
