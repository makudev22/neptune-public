<?php


declare(strict_types=1);

namespace pocketmine\tile;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

class DecoratedPot extends Spawnable
{

	protected function readSaveData(CompoundTag $nbt) : void
	{

	}

	protected function writeSaveData(CompoundTag $nbt) : void
	{

	}

	protected function addAdditionalSpawnData(CompoundTag $nbt, int $protocolVersion) : void
	{
		$nbt->setTag(new ListTag("sherds", [])); //TODO:
	}
}
