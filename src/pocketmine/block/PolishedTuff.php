<?php


declare(strict_types=1);

namespace pocketmine\block;

class PolishedTuff extends Tuff
{
	protected $id = self::POLISHED_TUFF;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Polished Tuff";
	}
}
