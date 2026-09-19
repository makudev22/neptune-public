<?php


declare(strict_types=1);

namespace pocketmine\level\generator\heightproviders;

use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

interface HeightProvider {

	public function sample(Random $random, ChunkManager $level) : int;

}
