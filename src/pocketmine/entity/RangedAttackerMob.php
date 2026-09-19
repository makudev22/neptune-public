<?php


declare(strict_types=1);

namespace pocketmine\entity;

interface RangedAttackerMob
{
	public function onRangedAttackToTarget(Entity $target, float $power) : void;
}
