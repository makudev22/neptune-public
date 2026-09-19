<?php


declare(strict_types=1);

namespace pocketmine\tile;

use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;

class Bed extends Spawnable
{
	public const TAG_COLOR = "color";
	/** @var int */
	private $color = 14; //default to old red

	public function getColor() : int
	{
		return $this->color;
	}

	/**
	 * @return void
	 */
	public function setColor(int $color)
	{
		$this->color = $color & 0xf;
		$this->onChanged();
	}

	protected function readSaveData(CompoundTag $nbt) : void
	{
		$this->color = $nbt->getByte(self::TAG_COLOR, 14, true);
	}

	protected function writeSaveData(CompoundTag $nbt) : void
	{
		$nbt->setByte(self::TAG_COLOR, $this->color);
	}

	protected function addAdditionalSpawnData(CompoundTag $nbt, int $protocolVersion) : void
	{
		$nbt->setByte(self::TAG_COLOR, $this->color);
	}

	protected static function createAdditionalNBT(CompoundTag $nbt, Vector3 $pos, ?int $face = null, ?Item $item = null, ?Player $player = null) : void
	{
		if ($item !== null) {
			$nbt->setByte(self::TAG_COLOR, $item->getDamage());
		}
	}
}
