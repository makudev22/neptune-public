<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\trunkplacers;

use pocketmine\level\ChunkManager;
use pocketmine\level\generator\feature\configurations\TreeConfiguration;
use pocketmine\level\generator\feature\setter\Setter;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use function cos;
use function sin;
use const M_PI;

class MegaJungleTrunkPlacer extends GiantTrunkPlacer {

	public function type() : TrunkPlacerType {
		return TrunkPlacerType::MEGA_JUNGLE_TRUNK_PLACER;
	}

	public function placeTrunk(ChunkManager $level, Setter $trunkSetter, Random $random, int $treeHeight, Vector3 $origin, TreeConfiguration $config) : array {
		$attachments = parent::placeTrunk($level, $trunkSetter, $random, $treeHeight, $origin, $config);

		for ($branchHeight = $treeHeight - 2 - $random->nextBoundedInt(4); $branchHeight > $treeHeight / 2; $branchHeight -= 2 + $random->nextBoundedInt(4)) {
			$angle = $random->nextFloat() * (float) (M_PI * 2);
			for ($b = 0; $b < 5; $b++) {
				$bx = (int) (1.5 + cos($angle) * $b);
				$bz = (int) (1.5 + sin($angle) * $b);
				$pos = $origin->add($bx, $branchHeight - 3 + $b / 2, $bz);
				$this->placeLog($level, $trunkSetter, $random, $pos, $config);
			}

			$attachments[] = new FoliageAttachment($origin->add($bx, $branchHeight, $bz), -2, false);
		}

		return $attachments;
	}
}
