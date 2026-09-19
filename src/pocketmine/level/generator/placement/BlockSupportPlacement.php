<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\level\generator\feature\blocksupport\BlockSupport;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class BlockSupportPlacement extends PlacementModifier {

	public function __construct(
		private BlockSupport $blockSupport
	){}

	public function getPositions(PlacementContext $context, Random $random, Vector3 $origin) : array{
		return $this->blockSupport->isValidPosition($context->getLevel(), $origin) ? [$origin] : [];
	}
}
