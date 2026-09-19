<?php


declare(strict_types=1);

namespace pocketmine\level\sound;

use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\convert\block\BlockProtocolConvertor;
use pocketmine\network\mcpe\convert\block\RuntimeBlockMapping;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class PressurePlateDeactivateSound extends Sound
{
	public function __construct(
		Vector3 $pos,
		private Block $block
	) {
		parent::__construct($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());
	}

	public function encode()
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_560) {
			$block = BlockProtocolConvertor::getInstance()->get($this->block, $this->protocol) ?? $this->block;
			return [LevelSoundEventPacket::nonActorSound(
				LevelSoundEventPacket::SOUND_PRESSURE_PLATE_CLICK_OFF,
				$this,
				false,
				RuntimeBlockMapping::getInstance($this->protocol)->toRuntimeId($block->getFullId())
			)];
		} else {
			return [LevelSoundEventPacket::nonActorSound(LevelSoundEventPacket::SOUND_POWER_OFF, $this, false)];
		}
	}

	public function isUseProtocol() : bool
	{
		return true;
	}
}
