<?php


declare(strict_types=1);

namespace pocketmine\event\entity;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;
use pocketmine\level\Position;
use pocketmine\utils\Utils;

/**
 * Called when a entity explodes
 * @phpstan-extends EntityEvent<Entity>
 */
class EntityExplodeEvent extends EntityEvent implements Cancellable
{
	/** @var Position */
	protected $position;

	/** @var Block[] */
	protected $blocks;

	/** @var float */
	protected $yield;

	/** @var Block[] */
	private $ignitions;

	/**
	 * @param Block[] $blocks
	 * @param Block[] $ignitions
	 */
	public function __construct(Entity $entity, Position $position, array $blocks, float $yield, array $ignitions = [])
	{
		$this->entity = $entity;
		$this->position = $position;
		$this->blocks = $blocks;
		$this->yield = $yield;
		$this->ignitions = $ignitions;

		if ($yield < 0.0 || $yield > 100.0) {
			throw new \InvalidArgumentException("Yield must be in range 0.0 - 100.0");
		}
	}

	public function getPosition() : Position
	{
		return $this->position;
	}

	/**
	 * @return Block[]
	 */
	public function getBlockList() : array
	{
		return $this->blocks;
	}

	/**
	 * @param Block[] $blocks
	 */
	public function setBlockList(array $blocks) : void
	{
		Utils::validateArrayValueType($blocks, function (Block $_) : void { });
		$this->blocks = $blocks;
	}

	public function getYield() : float
	{
		return $this->yield;
	}

	public function setYield(float $yield) : void
	{
		if ($yield < 0.0 || $yield > 100.0) {
			throw new \InvalidArgumentException("Yield must be in range 0.0 - 100.0");
		}

		$this->yield = $yield;
	}

	/**
	 * Set the list of blocks that will be replaced by fire.
	 *
	 * @param Block[] $ignitions
	 */
	public function setIgnitions(array $ignitions) : void
	{
		Utils::validateArrayValueType($ignitions, fn (Block $block) => null);
		$this->ignitions = $ignitions;
	}

	/**
	 * Returns a list of affected blocks that will be replaced by fire.
	 *
	 * @return Block[]
	 */
	public function getIgnitions() : array
	{
		return $this->ignitions;
	}
}
