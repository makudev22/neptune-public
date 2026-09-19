<?php


declare(strict_types=1);

namespace pocketmine\utils\valueproviders;

use pocketmine\utils\Random;

abstract class IntProvider {

	public abstract function sample(Random $random) : int;

	public abstract function getMinValue() : int;

	public abstract function getMaxValue() : int;

	public abstract function getType() : IntProviderType;

}
