<?php


declare(strict_types=1);

namespace pocketmine\entity\projectile;

use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\level\sound\PotionSplashSound;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\utils\Color;

use function mt_rand;

class ExperienceBottle extends Throwable
{
	public const NETWORK_ID = self::XP_BOTTLE;

	protected $gravity = 0.07;

	public function getResultDamage() : int
	{
		return -1;
	}

	public function onHit(ProjectileHitEvent $event) : void
	{
		$this->level->broadcastLevelEvent($this, LevelEventPacket::EVENT_PARTICLE_SPLASH, (new Color(0x38, 0x5d, 0xc6))->toARGB());
		$this->broadcastSound(new PotionSplashSound($this));

		$this->level->dropExperience($this, mt_rand(3, 11));
	}
}
