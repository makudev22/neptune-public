<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

use pocketmine\entity\Mob;
use pocketmine\utils\Random;

abstract class Behavior
{
	/** @var Mob */
	protected $mob;
	/** @var Random */
	protected $random;
	/** @var int */
	protected $mutexBits = 0;

	public function __construct(Mob $mob)
	{
		$this->mob = $mob;
		$this->random = $mob->random;
	}

	abstract public function canStart() : bool;

	public function onStart() : void
	{
	}

	public function canContinue() : bool
	{
		return $this->canStart();
	}

	public function onTick() : void
	{
	}

	public function onEnd() : void
	{
	}

	public function setMutexBits(int $bit) : void
	{
		$this->mutexBits = $bit;
	}

	public function getMutexBits() : int
	{
		return $this->mutexBits;
	}

	public function isMutable() : bool
	{
		return true;
	}
}
