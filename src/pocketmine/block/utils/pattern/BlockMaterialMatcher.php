<?php


declare(strict_types=1);

namespace pocketmine\block\utils\pattern;

use pocketmine\block\Block;
use function array_flip;

class BlockMaterialMatcher{

	/** @var callable(Block): bool */
	private $predicate;

	/**
	 * @param callable(Block): bool $predicate
	 */
	private function __construct(callable $predicate){
		$this->predicate = $predicate;
	}

	/**
	 * @param callable(Block): bool $predicate
	 */
	public static function forPredicate(callable $predicate) : self{
		return new self($predicate);
	}

	/**
	 * @param int[] $blockIds
	 */
	public static function forBlockIds(array $blockIds) : self{
		$ids = array_flip($blockIds);
		return new self(static function(Block $block) use ($ids) : bool{
			return isset($ids[$block->getId()]);
		});
	}

	public static function forFlammable() : self{
		return new self(static function(Block $block) : bool{
			return $block->isFlammable();
		});
	}

	public static function forSolid() : self{
		return new self(static function(Block $block) : bool{
			return $block->isSolid();
		});
	}

	public function apply(Block $block) : bool{
		return ($this->predicate)($block);
	}
}
