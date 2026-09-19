<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\TreeGrower;

class PaleOakSapling extends Sapling
{
	protected $id = self::PALE_OAK_SAPLING;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Pale Oak Sapling";
	}

	public function getTreeGrower() : ?TreeGrower
	{
		return TreeGrower::PALE_OAK();
	}

	public function getVariantBitmask() : int
	{
		return 0x00;
	}

	public function getReadyBitmask() : int {
		return 0x01;
	}
}
