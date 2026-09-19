<?php


declare(strict_types=1);

namespace pocketmine\entity\projectile;

use pocketmine\block\Block;
use pocketmine\math\RayTraceResult;

abstract class Throwable extends Projectile
{
	public float $width = 0.25;
	public float $height = 0.25;

	protected $gravity = 0.03;
	protected $drag = 0.01;

	protected function onHitBlock(Block $blockHit, RayTraceResult $hitResult) : void
	{
		parent::onHitBlock($blockHit, $hitResult);
		$this->flagForDespawn();
	}
}
