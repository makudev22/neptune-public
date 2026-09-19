<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\convert\block;

use pocketmine\block\Block;

interface BlockConvertor {

	public function to(Block $block, int $protocolVersion) : ?Block;

}
