<?php


declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

/**
 * Called when a player is awarded an achievement
 */
class PlayerAchievementAwardedEvent extends PlayerEvent implements Cancellable
{
	/** @var string */
	protected $achievement;

	public function __construct(Player $player, string $achievementId)
	{
		$this->player = $player;
		$this->achievement = $achievementId;
	}

	public function getAchievement() : string
	{
		return $this->achievement;
	}
}
