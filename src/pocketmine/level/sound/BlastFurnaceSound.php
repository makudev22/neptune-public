<?php


declare(strict_types=1);

namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class BlastFurnaceSound extends Sound
{
	public function __construct(Vector3 $pos)
	{
		parent::__construct($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());
	}

	public function encode()
	{
		if ($this->protocol < ProtocolInfo::PROTOCOL_407) {
			return [LevelSoundEventPacket::nonActorSound(LevelSoundEventPacket::SOUND_BLOCK_FURNACE_LIT, $this, false)];
		} else {
			return [LevelSoundEventPacket::nonActorSound(LevelSoundEventPacket::SOUND_BLOCK_BLASTFURNACE_FIRE_CRACKLE, $this, false)];
		}
	}

	public function isUseProtocol() : bool{
		return true;
	}
}
