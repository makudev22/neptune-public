<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\CopperDoor;
use pocketmine\block\utils\CopperMaterial;
use pocketmine\level\sound\CopperWaxApplySound;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;

class Honeycomb extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::HONEYCOMB, $meta, "Honeycomb");
	}

	public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector) : bool{
		if ($blockClicked instanceof CopperMaterial) {
			if(!$blockClicked->isWaxed()){
				$level = $blockClicked->getLevel();
				$level->setBlock($blockClicked, BlockFactory::get($blockClicked->getWaxedId(), $blockClicked->getDamage()), true);
				if ($blockClicked instanceof CopperDoor) {
					$other = $blockClicked->getSide((($blockClicked->getDamage() & 0x08) !== 0) ? Facing::DOWN : Facing::UP);
					if ($other instanceof CopperDoor) {
						$level->setBlock($other, BlockFactory::get($blockClicked->getWaxedId(), $other->getDamage()), true);
					}
				}

				//TODO: orange particles are supposed to appear when applying wax
				$level->addSound(new CopperWaxApplySound($blockClicked));
				$this->pop();
				return true;
			}
		}

		return parent::onActivate($player, $blockReplace, $blockClicked, $face, $clickVector);
	}

	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData
	{
		if ($playerProtocol < ProtocolInfo::PROTOCOL_407) {
			return new TranslatedItemData(ItemIds::BLAZE_POWDER, $this->getDamage(), $this->getName());
		}

		return parent::getItemProtocol($playerProtocol);
	}
}
