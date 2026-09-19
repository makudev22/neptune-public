<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

class EntityLink
{
	public const TYPE_REMOVE = 0;
	public const TYPE_RIDER = 1;
	public const TYPE_PASSENGER = 2;

	public int $fromEntityUniqueId;
	public int $toEntityUniqueId;
	public int $type;
	public bool $immediate; //for dismounting on mount death
	public bool $causedByRider;
	public float $vehicleAngularVelocity;

	public function __construct(int $fromEntityUniqueId = -1, int $toEntityUniqueId = -1, int $type = self::TYPE_REMOVE, bool $immediate = false, bool $causedByRider = false, float $vehicleAngularVelocity = 0.0)
	{
		$this->fromEntityUniqueId = $fromEntityUniqueId;
		$this->toEntityUniqueId = $toEntityUniqueId;
		$this->type = $type;
		$this->immediate = $immediate;
		$this->causedByRider = $causedByRider;
		$this->vehicleAngularVelocity = $vehicleAngularVelocity;
	}
}
