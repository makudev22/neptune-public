<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\convert\block;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class OldMetaWallConvertor implements BlockConvertor {
	public function __construct(
		private int $oldLegacyMeta
	) {}

	public function to(Block $block, int $protocolVersion) : ?Block {
		if ($protocolVersion < ProtocolInfo::PROTOCOL_407) {
			return BlockFactory::get(BlockIds::COBBLESTONE_WALL, $this->oldLegacyMeta % 2);
		} elseif ($protocolVersion < ProtocolInfo::PROTOCOL_419) {
			return BlockFactory::get(BlockIds::COBBLESTONE_WALL, $this->oldLegacyMeta);
		}

		return null;
	}
}
