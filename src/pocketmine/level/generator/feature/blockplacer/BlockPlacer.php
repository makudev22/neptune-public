<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\blockplacer;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

interface BlockPlacer{

	public function place(ChunkManager $level, Vector3 $pos, Block $state, Random $random) : void;

}
