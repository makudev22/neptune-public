<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory;

final class ItemStackWrapper
{
	public function __construct(
		private int $stackId,
		private ItemStack $itemStack
	) {
	}

	public static function legacy(ItemStack $itemStack) : self
	{
		return new self($itemStack->getId() === 0 ? 0 : 1, $itemStack);
	}

	public function getStackId() : int
	{
		return $this->stackId;
	}

	public function getItemStack() : ItemStack
	{
		return $this->itemStack;
	}
}
