<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\convert\block;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class StemConvertor implements BlockConvertor {
	public function __construct() {
		//NOOP
	}

	public function to(Block $block, int $protocolVersion) : ?Block {
		if ($protocolVersion < ProtocolInfo::PROTOCOL_428 && $block->getDamage() > 7) {
			return BlockFactory::get($block->getId(), $block->getDamage() & 7);
		}

		return null;
	}
}
