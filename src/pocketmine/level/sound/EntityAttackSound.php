<?php


declare(strict_types=1);

namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

/**
 * Played when a player attacks a mob, dealing damage.
 */
final class EntityAttackSound extends Sound
{
	public function __construct(Vector3 $pos)
	{
		parent::__construct($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());
	}

	public function encode()
	{
		return [LevelSoundEventPacket::create(
			LevelSoundEventPacket::SOUND_ATTACK_STRONG, //TODO: seems like ATTACK is dysfunctional
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
