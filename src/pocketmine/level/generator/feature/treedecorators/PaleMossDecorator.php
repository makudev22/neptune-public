<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\treedecorators;

use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\math\Vector3;
use pocketmine\utils\Utils;
use function count;
use const PHP_INT_MAX;

class PaleMossDecorator extends TreeDecorator{
	public function __construct(
		private float $leavesProbability,
		private float $trunkProbability,
		private float $groundProbability
	) {}

	protected function type() : TreeDecoratorType {
		return TreeDecoratorType::PALE_MOSS;
	}

	public function place(TreeDecoratorContext $context) : void {
		$random = $context->random();
		$level = $context->level();
		$logs = Utils::shuffledCopy($context->logs(), $random);

		if (count($logs) !== 0) {
			$origin = null;
			$minY = PHP_INT_MAX;
			foreach ($logs as $pos) {
				if ($pos->getY() < $minY) {
					$minY = $pos->getY();
					$origin = $pos;
				}
			}

			if ($random->nextFloat() < $this->groundProbability) {
				//TODO: PALE_MOSS_PATCH
			}

			foreach ($context->logs() as $pos) {
				if ($random->nextFloat() < $this->trunkProbability) {
					$down = $pos->down();
					if ($context->isAir($down)) {
						$this->addMossHanger($down, $context);
					}
				}
			}

			foreach ($context->leaves() as $pos) {
				if ($random->nextFloat() < $this->leavesProbability) {
					$down = $pos->down();
					if ($context->isAir($down)) {
						$this->addMossHanger($down, $context);
					}
				}
			}
		}
	}

	private static function addMossHanger(Vector3 $pos, TreeDecoratorContext $context) : void {
		while ($context->isAir($pos->down()) && !($context->random()->nextFloat() < 0.5)) {
			$context->setBlock($pos, BlockFactory::get(BlockIds::PALE_HANGING_MOSS));
			$pos = $pos->down();
		}

		$context->setBlock($pos, BlockFactory::get(BlockIds::PALE_HANGING_MOSS)->setDamage(1));
	}
}
