<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

final class PlayerPermissions {

	private function __construct(){
		//NOOP
	}

	public const int CUSTOM = 3;
	public const int OPERATOR = 2;
	public const int MEMBER = 1;
	public const int VISITOR = 0;

}
