<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory;

final class ContainerIds
{
	private function __construct()
	{
		//NOOP
	}

	public const NONE = -1;
	public const INVENTORY = 0;
	public const FIRST = 1;
	public const LAST = 100;
	public const OFFHAND = 119;
	public const ARMOR = 120;
	public const CREATIVE = 121;
	public const HOTBAR = 122;
	public const FIXED_INVENTORY = 123;
	public const UI = 124;
	public const CONTAINER_ID_REGISTRY = 125;
	public const CONTAINER_ID_REGISTRY_INVENTORY = 126;

}
