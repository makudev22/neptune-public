<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\BlockToolType;
use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

class Shovel extends TieredTool
{
	public function getBlockToolType() : int
	{
		return BlockToolType::TYPE_SHOVEL;
	}

	public function getBlockToolHarvestLevel() : int
	{
		return $this->tier;
	}

	public function getAttackPoints() : int
	{
		return self::getBaseDamageFromTier($this->tier) - 3;
	}

	public function onDestroyBlock(Block $block) : bool
	{
		if ($block->getHardness() > 0) {
			return $this->applyDamage(1);
		}
		return false;
	}

	public function onAttackEntity(Entity $victim) : bool
	{
		return $this->applyDamage(2);
	}

	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData
	{
		if ($playerProtocol < ProtocolInfo::PROTOCOL_407) {
			if ($this->getId() === ItemIds::NETHERITE_SHOVEL) {
				return new TranslatedItemData(ItemIds::DIAMOND_SHOVEL, $this->getDamage(), $this->getName());
			}
		}

		return parent::getItemProtocol($playerProtocol);
	}
}
