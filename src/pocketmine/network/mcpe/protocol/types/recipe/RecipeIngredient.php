<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\recipe;

final class RecipeIngredient{
	public function __construct(
		private ?ItemDescriptor $descriptor,
		private int $count
	){}

	public function getDescriptor() : ?ItemDescriptor{
		return $this->descriptor;
	}

	public function getCount() : int{
		return $this->count;
	}
}
