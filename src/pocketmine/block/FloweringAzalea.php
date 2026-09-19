<?php


declare(strict_types=1);

namespace pocketmine\block;

class FloweringAzalea extends Azalea
{
	protected $id = self::FLOWERING_AZALEA;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Flowering Azalea";
	}
}
