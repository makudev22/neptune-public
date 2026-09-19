<?php


declare(strict_types=1);

namespace pocketmine\block;

class SnifferEgg extends Transparent
{
	public const int TYPE_NOT_CRACKED = 0;
	public const int TYPE_SLIGHTLY_CRACKED = 1;
	public const int TYPE_VERY_CRACKED = 2;

	protected $id = self::SNIFFER_EGG;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Sniffer Egg";
	}

	public function getHardness() : float
	{
		return 0.5;
	}

	public function getBlastResistance() : float
	{
		return 2.5;
	}

	public function getVariantBitmask() : int
	{
		return 0;
	}
}
