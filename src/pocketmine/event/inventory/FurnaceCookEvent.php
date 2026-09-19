<?php


declare(strict_types=1);

namespace pocketmine\event\inventory;

use pocketmine\event\block\BlockEvent;
use pocketmine\event\Cancellable;
use pocketmine\tile\Furnace;

class FurnaceCookEvent extends BlockEvent implements Cancellable
{
	/** @var Furnace */
	private $furnace;
	/** @var int */
	private $maxCookTime;

	public function __construct(Furnace $furnace, int $maxCookTime)
	{
		parent::__construct($furnace->getBlock());
		$this->maxCookTime = $maxCookTime;
		$this->furnace = $furnace;
	}

	public function getFurnace() : Furnace
	{
		return $this->furnace;
	}

	public function getMaxCookTime() : int
	{
		return $this->maxCookTime;
	}

	public function setMaxCookTime(int $maxCookTime) : void
	{
		$this->maxCookTime = $maxCookTime;
	}
}
