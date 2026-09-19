<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\recipe;

final class MaterialReducerRecipeOutput{
	public function __construct(
		private int $itemId,
		private int $count
	){}

	public function getItemId() : int{ return $this->itemId; }

	public function getCount() : int{ return $this->count; }
}
