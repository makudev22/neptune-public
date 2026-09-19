<?php


declare(strict_types=1);

namespace pocketmine\block;

class CrimsonButton extends WoodenButton
{
	protected $id = self::CRIMSON_BUTTON;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Crimson Button";
	}
}
