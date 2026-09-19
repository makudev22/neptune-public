<?php


declare(strict_types=1);

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\Player;

/**
 * Called when plants or crops grow.
 */
class StructureGrowEvent extends BlockEvent implements Cancellable
{
	/** @var Block[] */
	private $newStates;

	/** @var ?Player */
	private $player;

	public function __construct(Block $block, array $newStates, ?Player $player)
	{
		parent::__construct($block);
		$this->newStates = $newStates;
		$this->player = $player;
	}

	public function getNewState() : array
	{
		return $this->newStates;
	}

	public function setNewState(array $blocks) : void
	{
		$this->newStates = $blocks;
	}

	public function getPlayer() : ?Player
	{
		return $this->player;
	}
}
