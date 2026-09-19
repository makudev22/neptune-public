<?php


declare(strict_types=1);

namespace pocketmine\block;

class TintedGlass extends Glass
{
	protected $id = self::TINTED_GLASS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Tinted Glass";
	}
}
