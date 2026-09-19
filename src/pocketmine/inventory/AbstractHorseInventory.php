<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\entity\passive\AbstractHorse;
use pocketmine\item\Item;
use pocketmine\item\Saddle;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

abstract class AbstractHorseInventory extends BaseInventory
{
	/** @var AbstractHorse */
	protected $holder;

	public function __construct(AbstractHorse $holder, array $items = [], int $size = null, string $title = null)
	{
		$this->holder = $holder;
		parent::__construct($items, $size, $title);
	}

	public function setSaddle(Item $saddle) : void
	{
		$this->setItem(0, $saddle);

		$this->holder->setSaddled($saddle instanceof Saddle);
	}

	public function onSlotChange(int $index, Item $before, bool $send) : void
	{
		parent::onSlotChange($index, $before, $send);

		if ($index === 0) {
			$this->holder->setSaddled($this->getSaddle() instanceof Saddle);

			$this->holder->level->broadcastLevelSoundEvent($this->holder, LevelSoundEventPacket::SOUND_SADDLE);
		}
	}

	public function getSaddle() : Item
	{
		return $this->getItem(0);
	}

	/**
	 * @return AbstractHorse
	 */
	public function getHolder()
	{
		return $this->holder;
	}
}
