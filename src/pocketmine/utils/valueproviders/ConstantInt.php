<?php


declare(strict_types=1);

namespace pocketmine\utils\valueproviders;

use pocketmine\utils\Random;

class ConstantInt extends IntProvider {

	public function __construct(
		private int $value
	){}

	public static function of(int $value) : ConstantInt {
		return new ConstantInt($value);
	}

	public function getValue() : int {
		return $this->value;
	}

	public function sample(Random $random) : int {
		return $this->value;
	}

	public function getMinValue() : int {
		return $this->value;
	}

	public function getMaxValue() : int {
		return $this->value;
	}

	public function getType() : IntProviderType {
		return IntProviderType::CONSTANT;
	}
}
