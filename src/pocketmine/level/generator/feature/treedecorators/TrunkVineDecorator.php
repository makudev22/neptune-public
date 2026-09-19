<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\treedecorators;

use pocketmine\block\Vine;

class TrunkVineDecorator extends TreeDecorator{

	protected function type() : TreeDecoratorType {
		return TreeDecoratorType::TRUNK_VINE;
	}

	public function place(TreeDecoratorContext $context) : void {
		$random = $context->random();

		foreach ($context->logs() as $pos) {
			if ($random->nextBoundedInt(3) > 0) {
				$west = $pos->west();
				if ($context->isAir($west)) {
					$context->placeVine($west, Vine::FLAG_EAST);
				}
			}

			if ($random->nextBoundedInt(3) > 0) {
				$east = $pos->east();
				if ($context->isAir($east)) {
					$context->placeVine($east, Vine::FLAG_WEST);
				}
			}

			if ($random->nextBoundedInt(3) > 0) {
				$north = $pos->north();
				if ($context->isAir($north)) {
					$context->placeVine($north, Vine::FLAG_SOUTH);
				}
			}

			if ($random->nextBoundedInt(3) > 0) {
				$south = $pos->south();
				if ($context->isAir($south)) {
					$context->placeVine($south, Vine::FLAG_NORTH);
				}
			}
		}
	}
}
