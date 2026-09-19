<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\convert\block;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class NewWallConvertor implements BlockConvertor {
	public function __construct(
		private int $minimalProtocol
	) {}

	public function to(Block $block, int $protocolVersion) : ?Block {
		if ($protocolVersion < ProtocolInfo::PROTOCOL_419) {
			return BlockFactory::get(BlockIds::COBBLESTONE_WALL);
		} elseif ($protocolVersion < $this->minimalProtocol) {
			return BlockFactory::get(BlockIds::COBBLESTONE_WALL, $block->getDamage());
		}

		return null;
	}
}
