<?php


declare(strict_types=1);

namespace pocketmine\block\utils;

use pocketmine\block\Block;
use pocketmine\event\block\BlockDeathEvent;
use pocketmine\event\block\BlockFormEvent;
use pocketmine\event\block\BlockGrowEvent;
use pocketmine\event\block\BlockMeltEvent;
use pocketmine\event\block\BlockSpreadEvent;
use pocketmine\Player;

/**
 * Helper class to call block changing events and apply the results to the world.
 * TODO: try to further reduce the amount of code duplication here - while this is much better than before, it's still
 * very repetitive.
 */
final class BlockEventHelper{

	public static function grow(Block $oldState, Block $newState, ?Player $causingPlayer) : bool{
		$ev = new BlockGrowEvent($oldState, $newState, $causingPlayer);
		$ev->call();
		if($ev->isCancelled()){
			return false;
		}
		$newState = $ev->getNewState();

		$position = $oldState->asPosition();
		$position->level->setBlock($position, $newState);
		return true;
	}

	public static function spread(Block $oldState, Block $newState, Block $source) : bool{
		$ev = new BlockSpreadEvent($oldState, $source, $newState);
		$ev->call();
		if($ev->isCancelled()){
			return false;
		}
		$newState = $ev->getNewState();

		$position = $oldState->asPosition();
		$position->level->setBlock($position, $newState);
		return true;
	}

	public static function form(Block $oldState, Block $newState, Block $cause) : bool{
		$ev = new BlockFormEvent($oldState, $newState, $cause);
		$ev->call();
		if ($ev->isCancelled()) {
			return false;
		}
		$newState = $ev->getNewState();

		$position = $oldState->asPosition();
		$position->level->setBlock($position, $newState);
		return true;
	}

	public static function melt(Block $oldState, Block $newState) : bool{
		$ev = new BlockMeltEvent($oldState, $newState);
		$ev->call();
		if($ev->isCancelled()){
			return false;
		}
		$newState = $ev->getNewState();

		$position = $oldState->asPosition();
		$position->level->setBlock($position, $newState);
		return true;
	}

	public static function die(Block $oldState, Block $newState) : bool{
		$ev = new BlockDeathEvent($oldState, $newState);
		$ev->call();
		if($ev->isCancelled()){
			return false;
		}
		$newState = $ev->getNewState();

		$position = $oldState->asPosition();
		$position->level->setBlock($position, $newState);
		return true;
	}
}
