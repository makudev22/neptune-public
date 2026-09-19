<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\ItemIds;

class GlowFrame extends ItemFrame
{
	protected $id = self::GLOW_FRAME;

	protected $itemId = ItemIds::GLOW_FRAME;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Glow Frame";
	}
}
