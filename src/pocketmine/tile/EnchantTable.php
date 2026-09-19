<?php


declare(strict_types=1);

namespace pocketmine\tile;

class EnchantTable extends Spawnable implements Nameable
{
	use NameableTrait{
		loadName as readSaveData;
		saveName as writeSaveData;
	}

	public function getDefaultName() : string
	{
		return "Enchanting Table";
	}
}
