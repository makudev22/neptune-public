<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\entity;

/**
 * Affects which parameter of the target attribute is modified.
 */
final class AttributeModifierTargetOperand
{
	private function __construct()
	{
		//NOOP
	}

	public const MIN = 0;
	public const MAX = 1;
	public const CURRENT = 2;
}
