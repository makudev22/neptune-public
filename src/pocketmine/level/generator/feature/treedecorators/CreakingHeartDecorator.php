<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\treedecorators;

use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\block\Log;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\utils\Utils;
use function count;

class CreakingHeartDecorator extends TreeDecorator{
	public function __construct(
		private float $probability
	) {}

	protected function type() : TreeDecoratorType {
		return TreeDecoratorType::CREAKING_HEART;
	}

	public function place(TreeDecoratorContext $context) : void {
		$random = $context->random();
		$level = $context->level();
		$logs = $context->logs();
		if (count($logs) !== 0) {
			if (!($random->nextFloat() >= $this->probability)) {
				/** @var Vector3[] $heartPlacements */
				$heartPlacements = Utils::shuffledCopy($logs, $random);

				foreach ($heartPlacements as $pos) {
					$surrounded = true;

					foreach (Facing::ALL as $direction) {
						$adjacentPos = $pos->getSide($direction);
						$state = $level->getBlockAt($adjacentPos->getFloorX(), $adjacentPos->getFloorY(), $adjacentPos->getFloorZ());
						if (!($state instanceof Log)) {
							$surrounded = false;
							break;
						}
					}

					if ($surrounded) {
						$context->setBlock($pos, BlockFactory::get(BlockIds::CREAKING_HEART, 12)); //dormant and natural
						return;
					}
				}
			}
		}
	}
}
