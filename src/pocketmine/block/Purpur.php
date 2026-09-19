<?php


declare(strict_types=1);

namespace pocketmine\block;

class Purpur extends Quartz
{
	protected $id = self::PURPUR_BLOCK;

	public function getName() : string
	{
		static $names = [
			self::NORMAL => "Purpur Block",
			self::CHISELED => "Chiseled Purpur", //wtf?
			self::PILLAR => "Purpur Pillar"
		];

		return $names[$this->getVariant()] ?? "Unknown";
	}

	public function getHardness() : float
	{
		return 1.5;
	}

	public function getBlastResistance() : float
	{
		return 30;
	}
}
