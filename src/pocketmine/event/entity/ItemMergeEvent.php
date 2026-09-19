<?php


declare(strict_types=1);

namespace pocketmine\event\entity;

use pocketmine\entity\object\ItemEntity;
use pocketmine\event\Cancellable;

/**
 * Called when an item entity tries to merge into another item entity.
 *
 * @phpstan-extends EntityEvent<ItemEntity>
 */
class ItemMergeEvent extends EntityEvent implements Cancellable{

	public function __construct(
		ItemEntity $entity,
		protected ItemEntity $target
	){
		$this->entity = $entity;
	}

	/**
	 * Returns the merge destination.
	 */
	public function getTarget() : ItemEntity{
		return $this->target;
	}

}
