<?php


declare(strict_types=1);

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\entity\Entity;

/**
 * Called whenever the list of entities on a pressure plate changes.
 * Depending on the type of pressure plate, this might turn on/off its signal, or change the signal strength.
 */
final class PressurePlateUpdateEvent extends BaseBlockChangeEvent{
	/**
	 * @param Entity[] $activatingEntities
	 */
	public function __construct(
		Block $block,
		Block $newState,
		private array $activatingEntities
	){
		parent::__construct($block, $newState);
	}

	/**
	 * Returns a list of entities intersecting the pressure plate's activation box.
	 * If the pressure plate is about to deactivate, this list will be empty.
	 *
	 * @return Entity[]
	 */
	public function getActivatingEntities() : array{ return $this->activatingEntities; }
}
