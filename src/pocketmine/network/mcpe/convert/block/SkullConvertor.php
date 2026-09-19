<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\convert\block;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class SkullConvertor implements BlockConvertor {
	public function __construct() {
		//NOOP
	}

	public function to(Block $block, int $protocolVersion) : ?Block {
		if ($protocolVersion < ProtocolInfo::PROTOCOL_766) {
			return BlockFactory::get(BlockIds::SKULL_BLOCK, ($block->getDamage() % 6));
		}

		return null;
	}
}
