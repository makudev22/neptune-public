<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\ItemIds;

class LitSmoker extends Smoker
{
	protected $id = self::LIT_SMOKER;
	protected $itemId = ItemIds::SMOKER;

	public function getName() : string
	{
		return "Lit Smoker";
	}

	public function getLightLevel() : int
	{
		return 13;
	}
}
