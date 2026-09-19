<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

trait GetTypeIdFromConstTrait
{
	public function getTypeId() : int
	{
		return self::ID;
	}
}
