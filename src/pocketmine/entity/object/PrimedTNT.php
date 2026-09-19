<?php


declare(strict_types=1);

namespace pocketmine\entity\object;

use pocketmine\entity\Entity;
use pocketmine\entity\Explosive;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ExplosionPrimeEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\level\Explosion;
use pocketmine\level\Position;
use pocketmine\nbt\tag\ShortTag;

class PrimedTNT extends Entity implements Explosive
{
	public const NETWORK_ID = self::TNT;

	public float $width = 0.98;
	public float $height = 0.98;

	protected float $baseOffset = 0.49;

	protected $gravity = 0.04;
	protected $drag = 0.02;

	protected $fuse;

	public bool $canCollide = false;

	public function attack(EntityDamageEvent $source) : void
	{
		if ($source->getCause() === EntityDamageEvent::CAUSE_VOID) {
			parent::attack($source);
		}
	}

	protected function initEntity() : void
	{
		parent::initEntity();

		if ($this->namedtag->hasTag("Fuse", ShortTag::class)) {
			$this->fuse = $this->namedtag->getShort("Fuse");
		} else {
			$this->fuse = 80;
		}

		$this->setGenericFlag(self::DATA_FLAG_IGNITED, true);
		$this->propertyManager->setInt(self::DATA_FUSE_LENGTH, $this->fuse);
	}

	public function canCollideWith(Entity $entity) : bool
	{
		return false;
	}

	public function saveNBT() : void
	{
		parent::saveNBT();
		$this->namedtag->setShort("Fuse", $this->fuse, true); //older versions incorrectly saved this as a byte
	}

	public function entityBaseTick(int $tickDiff = 1) : bool
	{
		if ($this->closed) {
			return false;
		}

		$hasUpdate = parent::entityBaseTick($tickDiff);

		if ($this->fuse % 5 === 0) { //don't spam it every tick, it's not necessary
			$this->propertyManager->setInt(self::DATA_FUSE_LENGTH, $this->fuse);
		}

		if (!$this->isFlaggedForDespawn()) {
			$this->fuse -= $tickDiff;

			if ($this->fuse <= 0) {
				$this->flagForDespawn();
				$this->explode();
			}
		}

		return $hasUpdate || $this->fuse >= 0;
	}

	public function explode() : void{
		$ev = new ExplosionPrimeEvent($this, 4);
		$ev->call();
		if (!$ev->isCancelled()) {
			$explosion = new Explosion(Position::fromObject($this->add(0, $this->height / 2, 0), $this->level), $ev->getForce(), $this);
			if ($ev->isBlockBreaking()) {
				$explosion->explodeA();
			}
			$explosion->explodeB();
		}
	}

	public function getPickedItem() : ?Item
	{
		return ItemFactory::get(Item::TNT);
	}
}
