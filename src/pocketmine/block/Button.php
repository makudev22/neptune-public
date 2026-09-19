<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\level\sound\RedstonePowerOffSound;
use pocketmine\level\sound\RedstonePowerOnSound;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\Player;

abstract class Button extends Flowable
{
	public function canBeFlowedInto() : bool
	{
		return false;
	}

	public function getVariantBitmask() : int
	{
		return 0;
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool
	{
		if ($this->canBeSupportedAt($blockReplace->getSide(Facing::opposite($face)))) {
			$this->meta = $face;
			return parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
		}
		return false;
	}

	abstract protected function getActivationTime() : int;

	public function onActivate(Item $item, Player $player = null) : bool
	{
		if (!$this->isActivated()) {
			$this->meta ^= 0x08;
			$this->level->setBlock($this, $this);
			$this->level->scheduleDelayedBlockUpdate($this, $this->getActivationTime());
			$this->level->addSound(new RedstonePowerOnSound($this->add(0.5, 0.5, 0.5)));
		}
		return true;
	}

	public function onScheduledUpdate() : void
	{
		if ($this->isActivated()) {
			$this->meta ^= 0x08;
			$this->level->setBlock($this, $this);
			$this->level->addSound(new RedstonePowerOffSound($this->add(0.5, 0.5, 0.5)));
		}
	}

	public function onNearbyBlockChange() : void
	{
		$side = $this->getDamage();
		if ($this->isActivated()) {
			$side ^= 0x08;
		}
		if (!$this->canBeSupportedAt($this->getSide(Facing::opposite($side)))) {
			$this->level->useBreakOn($this);
		}
	}

	protected function canBeSupportedAt(Block $block) : bool
	{
		if (!$block->isTransparent()) {
			return true;
		}
		if (($this->meta & 0x07) == Facing::UP) {
			return $this->canStayOnFullSolid($block);
		}
		return $this->canSupportToFullSolid($block);
	}

	public function isActivated() : bool
	{
		return (($this->meta & 0x08) === 0x08);
	}
}
