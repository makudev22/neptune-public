<?php


declare(strict_types=1);

namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

/**
 * Played when a player attacks a mob, but fails to deal damage (e.g. cancelled or attack cooldown).
 */
final class EntityAttackNoDamageSound extends Sound
{
	public function __construct(Vector3 $pos)
	{
		parent::__construct($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());
	}

	public function encode()
	{
		return [LevelSoundEventPacket::create(
			LevelSoundEventPacket::SOUND_ATTACK_NODAMAGE,
			$this,
			-1,
			"minecraft:player",
			false,
			false,
			-1,
			null
		)];
	}
}
