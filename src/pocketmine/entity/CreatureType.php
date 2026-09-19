<?php


declare(strict_types=1);

namespace pocketmine\entity;

class CreatureType
{
	/** @var string */
	protected $creatureClass;
	/** @var int */
	protected $maxSpawn;
	/** @var int */
	protected $materialIn;
	/** @var bool */
	protected $peacefulCreature = false;

	public function __construct(string $creatureClass, int $maxSpawn, int $materialIn, bool $peacefulCreature)
	{
		$this->creatureClass = $creatureClass;
		$this->maxSpawn = $maxSpawn;
		$this->materialIn = $materialIn;
		$this->peacefulCreature = $peacefulCreature;
	}

	public function getCreatureClass() : string
	{
		return $this->creatureClass;
	}

	public function getMaxSpawn() : int
	{
		return $this->maxSpawn;
	}

	public function getMaterialIn() : int
	{
		return $this->materialIn;
	}

	public function isPeacefulCreature() : bool
	{
		return $this->peacefulCreature;
	}
}
