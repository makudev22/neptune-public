<?php


declare(strict_types=1);

namespace pocketmine\block;

class Log2 extends Log
{
	public const ACACIA = 0;
	public const DARK_OAK = 1;

	public function getName() : string
	{
		static $names = [
			0 => "Acacia Log",
			1 => "Dark Oak Log"
		];
		return $names[$this->getVariant()] ?? "Unknown";
	}
}
