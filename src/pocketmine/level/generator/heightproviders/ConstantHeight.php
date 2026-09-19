<?php


declare(strict_types=1);

namespace pocketmine\level\generator\heightproviders;

use pocketmine\level\ChunkManager;
use pocketmine\level\generator\verticalanchor\VerticalAnchor;
use pocketmine\utils\Random;

class ConstantHeight implements HeightProvider {

	public static function of(VerticalAnchor $value) : ConstantHeight {
		return new self($value);
	}

	public function __construct(
		private VerticalAnchor $value
	){}

	public function getValue() : VerticalAnchor {
		return $this->value;
	}

	public function sample(Random $random, ChunkManager $level) : int {
		return $this->value->resolveY($level);
	}
}
