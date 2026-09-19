<?php


declare(strict_types=1);

namespace pocketmine\block;

class AzaleaLeavesFlowered extends AzaleaLeaves
{
	protected $id = self::AZALEA_LEAVES_FLOWERED;

	public function getName() : string
	{
		return "Azalea Leaves Flowered";
	}
}
