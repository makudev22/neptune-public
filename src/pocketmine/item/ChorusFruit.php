<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Liquid;
use pocketmine\entity\Living;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\math\Vector3;

use function min;
use function mt_rand;

class ChorusFruit extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::CHORUS_FRUIT, $meta, "Chorus Fruit");
	}

	public function getFoodRestore() : int
	{
		return 4;
	}

	public function getSaturationRestore() : float
	{
		return 2.4;
	}

	public function requiresHunger() : bool
	{
		return false;
	}

	public function onConsume(Living $consumer)
	{
		$level = $consumer->getLevel();

		$minX = $consumer->getFloorX() - 8;
		$minY = min($consumer->getFloorY(), $consumer->getLevel()->getWorldHeight()) - 8;
		$minZ = $consumer->getFloorZ() - 8;

		$maxX = $minX + 16;
		$maxY = $minY + 16;
		$maxZ = $minZ + 16;

		for ($attempts = 0; $attempts < 16; ++$attempts) {
			$x = mt_rand($minX, $maxX);
			$y = mt_rand($minY, $maxY);
			$z = mt_rand($minZ, $maxZ);

			while ($y >= 0 && !$level->getBlockAt($x, $y, $z)->isSolid()) {
				$y--;
			}
			if ($y < 0) {
				continue;
			}

			$blockUp = $level->getBlockAt($x, $y + 1, $z);
			$blockUp2 = $level->getBlockAt($x, $y + 2, $z);
			if ($blockUp->isSolid() || $blockUp instanceof Liquid || $blockUp2->isSolid() || $blockUp2 instanceof Liquid) {
				continue;
			}

			//Sounds are broadcasted at both source and destination
			$level->addSound(new EndermanTeleportSound($consumer->asVector3()));
			$consumer->teleport(new Vector3($x + 0.5, $y + 1, $z + 0.5));
			$level->addSound(new EndermanTeleportSound($consumer->asVector3()));

			break;
		}
	}

	public function getCooldownTicks() : int
	{
		return 20;
	}
}
