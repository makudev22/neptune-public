<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Air;
use pocketmine\block\Bedrock;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\object\EnderCrystal;
use pocketmine\level\Location;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\Player;

use function count;

class EndCrystal extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::END_CRYSTAL, $meta, "End Crystal");
	}

	public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector) : bool
	{
		if ($face === Facing::UP && ($blockClicked->getId() === self::OBSIDIAN || $blockClicked instanceof Bedrock)) {
			$level = $blockClicked->getLevel();
			$entities = $level->getNearbyEntities(new AxisAlignedBB($blockClicked->getX(), $blockClicked->getY() + 1, $blockClicked->getZ(), $blockClicked->getX() + 1, $blockClicked->getY() + 3, $blockClicked->getZ() + 1));
			if (count($entities) === 0 && $level->getBlock($blockClicked->up()) instanceof Air && $level->getBlock($blockClicked->up(2)) instanceof Air) {
				$nbt = Entity::createBaseNBT(Location::fromObject($blockClicked->add(0.5, 1, 0.5)));

				$crystal = new EnderCrystal($level, $nbt);
				$crystal->spawnToAll();

				if ($player->isSurvival()) {
					$this->pop();
				}

				return true;
			}
		}
		return false;
	}
}
