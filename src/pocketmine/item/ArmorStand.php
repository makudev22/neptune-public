<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\object\ArmorStand as EntityArmorStand;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;

use function round;

class ArmorStand extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::ARMOR_STAND, $meta, "Armor Stand");
	}

	public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector) : bool
	{
		$entity = Entity::createEntity("ArmorStand", $player->level, Entity::createBaseNBT($blockReplace->asVector3()->add(0.5, 0, 0.5), null, $this->getDirection($player->getYaw())));

		if ($entity instanceof EntityArmorStand) {
			if ($player->isSurvival()) {
				$this->pop();
			}

			$entity->spawnToAll();
			$player->getLevel()->broadcastLevelEvent($player, LevelEventPacket::EVENT_SOUND_ARMOR_STAND_PLACE);
			return true;
		}

		return false;
	}

	public function getDirection(float $yaw)
	{
		return (round($yaw / 22.5 / 2) * 45) - 180;
	}

	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData
	{
		if ($playerProtocol < ProtocolInfo::PROTOCOL_407) {
			return new TranslatedItemData(ItemIds::PLANKS, $this->getDamage(), $this->getName());
		}

		return parent::getItemProtocol($playerProtocol);
	}
}
