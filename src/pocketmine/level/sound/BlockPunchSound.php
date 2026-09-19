<?php


declare(strict_types=1);

namespace pocketmine\level\sound;

use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\convert\block\BlockProtocolConvertor;
use pocketmine\network\mcpe\convert\block\RuntimeBlockMapping;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

/**
 * Played when a player attacks a block in survival, attempting to break it.
 */
final class BlockPunchSound extends Sound
{
	public function __construct(
		Vector3 $pos,
		private Block $block
	) {
		parent::__construct($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());
	}

	public function encode()
	{
		$block = BlockProtocolConvertor::getInstance()->get($this->block, $this->protocol) ?? $this->block;
		return [LevelSoundEventPacket::nonActorSound(
			LevelSoundEventPacket::SOUND_HIT,
			$this,
			false,
			($this->protocol >= ProtocolInfo::PROTOCOL_407 ?
				RuntimeBlockMapping::getInstance($this->protocol)->toRuntimeId($block->getFullId()) :
				$block->getId())
		)];
	}

	public function isUseProtocol() : bool
	{
		return true;
	}
}
