<?php


declare(strict_types=1);

namespace pocketmine\block;

class RawIron extends Raw
{
	protected $id = self::RAW_IRON_BLOCK;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Raw Iron Block";
	}
}
