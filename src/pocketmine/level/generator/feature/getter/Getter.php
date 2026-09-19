<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\getter;

use pocketmine\block\Block;
use pocketmine\math\Vector3;

interface Getter {

	public function get(Vector3 $pos) : Block;

}
