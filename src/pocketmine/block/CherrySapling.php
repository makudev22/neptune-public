<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\TreeGrower;

class CherrySapling extends Sapling
{
	protected $id = self::CHERRY_SAPLING;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Cherry Sapling";
	}

	public function getTreeGrower() : ?TreeGrower
	{
		return TreeGrower::CHERRY();
	}

	public function getVariantBitmask() : int
	{
		return 0x00;
	}

	public function getReadyBitmask() : int {
		return 0x01;
	}
}
