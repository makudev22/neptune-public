<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

use pocketmine\entity\Mob;
use pocketmine\Player;

class LookAtPlayerBehavior extends LookAtEntityBehavior
{
	public function __construct(Mob $mob, float $lookDistance = 8.0)
	{
		parent::__construct($mob, Player::class, $lookDistance);
	}
}
