<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\stateproviders;

use pocketmine\utils\Random;
use pocketmine\utils\valueproviders\IntProvider;

abstract class RandomizedIntStateSetter{

	public function __construct(
		private IntProvider $values
	){}

	public function set(int $oldMeta, Random $random) : int{
		return $this->changeMeta($oldMeta, $this->values->sample($random));
	}

	abstract protected function changeMeta(int $oldMeta, int $value) : int;
}
