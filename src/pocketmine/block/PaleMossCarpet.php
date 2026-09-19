<?php


declare(strict_types=1);

namespace pocketmine\block;

class PaleMossCarpet extends Carpet
{
	protected $id = self::PALE_MOSS_CARPET;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Pale Moss Carpet";
	}
}
