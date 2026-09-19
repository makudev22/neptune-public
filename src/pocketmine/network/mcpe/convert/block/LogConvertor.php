<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\convert\block;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class LogConvertor implements BlockConvertor {
	public function __construct() {
		//NOOP
	}

	public function to(Block $block, int $protocolVersion) : ?Block {
		if ($protocolVersion >= ProtocolInfo::PROTOCOL_407 && $block->getDamage() >= 12) {
			return BlockFactory::get(BlockIds::WOOD, $block->getVariant());
		}

		return null;
	}
}
