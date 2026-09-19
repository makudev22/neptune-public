<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\cache;

use pocketmine\item\Item;

/**
 * Info for an item group in the creative inventory menu.
 */
final class CreativeGroup{
	/**
	 * @param string $name Tooltip shown to the player on hover
	 * @param Item   $icon Item shown when the group is collapsed
	 */
	public function __construct(
		private readonly string $name,
		private readonly Item $icon
	){}

	public function getName() : string{ return $this->name; }

	public function getIcon() : Item{ return clone $this->icon; }
}
