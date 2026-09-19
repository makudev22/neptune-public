<?php


declare(strict_types=1);

namespace pocketmine\event\entity;

use pocketmine\entity\EffectInstance;
use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;

/**
 * @phpstan-extends EntityEvent<Entity>
 */
class EntityEffectEvent extends EntityEvent implements Cancellable
{
	/** @var EffectInstance */
	private $effect;

	public function __construct(Entity $entity, EffectInstance $effect)
	{
		$this->entity = $entity;
		$this->effect = $effect;
	}

	public function getEffect() : EffectInstance
	{
		return $this->effect;
	}
}
