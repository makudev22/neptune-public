<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

final class InteractionMode
{
	private function __construct()
	{
		//NOOP
	}

	public const TOUCH = 0;
	public const CROSSHAIR = 1;
	public const CLASSIC = 2; //???
}
