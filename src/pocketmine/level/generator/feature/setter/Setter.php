<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\setter;

use pocketmine\block\Block;
use pocketmine\math\Vector3;

interface Setter {

	public function set(Vector3 $pos, Block $block) : void;

}
