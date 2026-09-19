<?php


declare(strict_types=1);

namespace pocketmine\block;

class PaleOakButton extends WoodenButton
{
	protected $id = self::PALE_OAK_BUTTON;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Pale Oak Button";
	}
}
