<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\treedecorators;

use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\math\Facing;
use function count;

class CocoaDecorator extends TreeDecorator{

	public function __construct(
		private float $probability
	){}

	protected function type() : TreeDecoratorType {
		return TreeDecoratorType::COCOA;
	}

	public function place(TreeDecoratorContext $context) : void {
		$random = $context->random();
		if (!($random->nextFloat() >= $this->probability)) {
			$logs = $context->logs();
			if (count($logs) !== 0) {
				$treeY = $logs[0]->getY();
				foreach ($logs as $pos) {
					if ($pos->getY() - $treeY > 2) {
						continue;
					}

					foreach (Facing::HORIZONTAL as $direction) {
						if ($random->nextFloat() <= 0.25) {
							$opposite = Facing::opposite($direction);
							$cocoaPos = $pos->add(Facing::OFFSET[$opposite][0], 0, Facing::OFFSET[$opposite][2]);
							if ($context->isAir($cocoaPos)) {
								$context->setBlock(
									$cocoaPos,
									BlockFactory::get(BlockIds::COCOA, Facing::opposite(match($direction){
											Facing::NORTH => 2,
											Facing::SOUTH => 0,
											Facing::WEST => 1,
											Facing::EAST => 3
										}) | ($random->nextBoundedInt(3) << 2))
								);
							}
						}
					}
				}
			}
		}
	}
}
