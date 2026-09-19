<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\treedecorators;

use pocketmine\block\Vine;
use pocketmine\math\Vector3;

class LeaveVineDecorator extends TreeDecorator{

	public function __construct(
		private float $probability
	){}

	protected function type() : TreeDecoratorType {
		return TreeDecoratorType::LEAVE_VINE;
	}

	public function place(TreeDecoratorContext $context) : void {
		$random = $context->random();

		foreach ($context->leaves() as $pos) {
			if ($random->nextFloat() < $this->probability) {
				$west = $pos->west();
				if ($context->isAir($west)) {
					$this->addHangingVine($west, Vine::FLAG_EAST, $context);
				}
			}

			if ($random->nextFloat() < $this->probability) {
				$east = $pos->east();
				if ($context->isAir($east)) {
					$this->addHangingVine($east, Vine::FLAG_WEST, $context);
				}
			}

			if ($random->nextFloat() < $this->probability) {
				$north = $pos->north();
				if ($context->isAir($north)) {
					$this->addHangingVine($north, Vine::FLAG_SOUTH, $context);
				}
			}

			if ($random->nextFloat() < $this->probability) {
				$south = $pos->south();
				if ($context->isAir($south)) {
					$this->addHangingVine($south, Vine::FLAG_NORTH, $context);
				}
			}
		}
	}

	private function addHangingVine(Vector3 $pos, int $direction, TreeDecoratorContext $context) : void {
		$context->placeVine($pos, $direction);
		$maxDir = 4;

		for ($var4 = $pos->down(); $context->isAir($var4) && $maxDir > 0; $maxDir--) {
			$context->placeVine($var4, $direction);
			$var4 = $var4->down();
		}
	}
}
