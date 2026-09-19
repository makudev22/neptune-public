<?php


declare(strict_types=1);

namespace pocketmine\block\utils\pattern;

use pocketmine\block\Block;

class BlockStateMatcher{
	private int $blockId;

	/** @var array<callable(Block): bool> */
	private array $propertyPredicates = [];

	private function __construct(int $blockId){
		$this->blockId = $blockId;
	}

	public static function forBlock(Block $block) : self{
		return new self($block->getId());
	}

	public static function forBlockId(int $blockId) : self{
		return new self($blockId);
	}

	/**
	 * @param callable(Block): bool $predicate
	 */
	public function where(callable $predicate) : self{
		$this->propertyPredicates[] = $predicate;
		return $this;
	}

	public function apply(Block $block) : bool{
		if($block->getId() !== $this->blockId){
			return false;
		}
		foreach($this->propertyPredicates as $predicate){
			if(!$predicate($block)){
				return false;
			}
		}
		return true;
	}
}
