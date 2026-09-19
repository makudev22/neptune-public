<?php


declare(strict_types=1);

namespace pocketmine\block;

class BambooButton extends WoodenButton
{
	protected $id = self::BAMBOO_BUTTON;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Bamboo Button";
	}
}
