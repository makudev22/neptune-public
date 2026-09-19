<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

final class EditorWorldType
{
	private function __construct()
	{
		//NOOP
	}

	public const NON_EDITOR = 0;
	public const PROJECT = 1;
	public const TEST_LEVEL = 2;
	public const REALMS_UPLOAD = 3;
}
