<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\BlockToolType;
use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

class Hoe extends TieredTool
{
	public function getBlockToolType() : int
	{
		return BlockToolType::TYPE_HOE;
	}

	public function onAttackEntity(Entity $victim) : bool
	{
		return $this->applyDamage(1);
	}

	public function onDestroyBlock(Block $block) : bool
	{
		if ($block->getHardness() > 0) {
			return $this->applyDamage(1);
		}
		return false;
	}

	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData
	{
		if ($playerProtocol < ProtocolInfo::PROTOCOL_407) {
			if ($this->getId() === ItemIds::NETHERITE_HOE) {
				return new TranslatedItemData(ItemIds::DIAMOND_HOE, $this->getDamage(), $this->getName());
			}
		}

		return parent::getItemProtocol($playerProtocol);
	}
}
