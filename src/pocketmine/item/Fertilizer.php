<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\Growable;
use pocketmine\level\particle\BoneMealParticle;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Fertilizer extends Item {

	public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector) : bool{
		if ($blockClicked instanceof Growable) {
			$random = $player->level->random;
			if ($blockClicked->canGrow($random, $player)) {
				if ($blockClicked->canUseBonemeal($random, $player)) {
					$blockClicked->grow($random, $player);
				}

				$this->pop();
				$player->level->addParticle(new BoneMealParticle($blockClicked));
				return true;
			}
		}

		return parent::onActivate($player, $blockReplace, $blockClicked, $face, $clickVector);
	}
}
