<?php


declare(strict_types=1);

namespace pocketmine\block;

class RedSandstone extends Sandstone
{
	protected $id = self::RED_SANDSTONE;

	public function getName() : string
	{
		static $names = [
			self::NORMAL => "Red Sandstone",
			self::CHISELED => "Chiseled Red Sandstone",
			self::SMOOTH => "Smooth Red Sandstone"
		];
		return $names[$this->getVariant()] ?? "Unknown";
	}
}
