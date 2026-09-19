<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

final class AttributeFloatOperation{
	public const OVERRIDE = 0;
	public const ALPHA_BLEND = 1;
	public const ADD = 2;
	public const SUBTRACT = 3;
	public const MULTIPLY = 4;
	public const MINIMUM = 5;
	public const MAXIMUM = 6;
}
