<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\setter;

use pocketmine\math\Vector3;

interface CollectorSetter extends Setter {
	public function isSet(Vector3 $pos) : bool;

}
