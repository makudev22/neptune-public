<?php


declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\math\Vector3;
use pocketmine\Player;

abstract class Vehicle extends Entity implements Rideable
{
	public function onFirstInteract(Player $player, Vector3 $clickPos) : bool
	{
		return $player->mountEntity($this);
	}

	public function setHealth(float $amount) : void
	{
		parent::setHealth($amount);

		$this->propertyManager->setInt(self::DATA_HEALTH, (int) $amount);
	}

	public function getHurtTime() : int
	{
		return $this->propertyManager->getInt(self::DATA_HURT_TIME) ?? 0;
	}

	public function setHurtTime(int $value) : void
	{
		$this->propertyManager->setInt(self::DATA_HURT_TIME, $value);
	}

	public function getHurtDirection() : int
	{
		return $this->propertyManager->getInt(self::DATA_HURT_DIRECTION) ?? 0;
	}

	public function setHurtDirection(int $value) : void
	{
		$this->propertyManager->setInt(self::DATA_HURT_DIRECTION, $value);
	}
}
