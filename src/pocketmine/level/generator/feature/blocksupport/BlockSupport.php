<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\blocksupport;

use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;

interface BlockSupport {

	public function isValidPosition(ChunkManager $level, Vector3 $origin) : bool;

}
