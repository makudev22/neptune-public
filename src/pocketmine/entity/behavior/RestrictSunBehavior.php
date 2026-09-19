<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

class RestrictSunBehavior extends Behavior
{
	public function canStart() : bool
	{
		return $this->mob->level->isDayTime();
	}

	public function onStart() : void
	{
		$this->mob->getNavigator()->setAvoidsSun(true);
	}

	public function onEnd() : void
	{
		$this->mob->getNavigator()->setAvoidsSun(false);
	}
}
