<?php


declare(strict_types=1);

namespace pocketmine\block;

class PolishedBlackstoneButton extends StoneButton
{
	protected $id = self::POLISHED_BLACKSTONE_BUTTON;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Polished Blackstone Button";
	}
}
