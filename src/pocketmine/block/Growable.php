<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\Player;
use pocketmine\utils\Random;

interface Growable {

	public function canGrow(Random $random, ?Player $player) : bool;

	public function canUseBonemeal(Random $random, ?Player $player) : bool;

	public function grow(Random $random, ?Player $player) : void;

}
