<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\network\mcpe\convert\block\BlockProtocolConvertor;

/**
 * Class used for Items that can be Blocks
 */
class ItemBlock extends Item
{
	private int $blockFullId;

	/**
	 * @param int $meta usually 0-15 (placed blocks may only have meta values 0-15)
	 */
	public function __construct(int $id, int $meta, Block $block)
	{
		parent::__construct($id, $meta, $block->getName());
		$this->blockFullId = $block->getFullId();
	}

	public function getBlock() : Block
	{
		return BlockFactory::fromFullBlock($this->blockFullId);
	}

	public function getVanillaName() : string
	{
		return $this->getBlock()->getName();
	}

	public function getFuelTime() : int
	{
		return $this->getBlock()->getFuelTime();
	}

	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData
	{
		$blockProtocol = BlockProtocolConvertor::getInstance()->get($this->getBlock(), $playerProtocol);
		if ($blockProtocol === null) {
			return null;
		}

		$item = $blockProtocol->asItem();
		return new TranslatedItemData($item->getId(), $item->getDamage(), $this->getName());
	}
}
