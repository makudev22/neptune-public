<?php


declare(strict_types=1);

namespace pocketmine\block;

class RawGold extends Raw
{
	protected $id = self::RAW_GOLD_BLOCK;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Raw Gold Block";
	}
}
