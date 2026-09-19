<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

final class AttributeBoolOperation{
	public const OVERRIDE = 0;
	public const ALPHA_BLEND = 1;
	public const AND = 2;
	public const NAND = 3;
	public const OR = 4;
	public const NOR = 5;
	public const XOR = 6;
	public const XNOR = 7;
}
