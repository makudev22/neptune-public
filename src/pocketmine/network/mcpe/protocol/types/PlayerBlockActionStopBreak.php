<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;

final class PlayerBlockActionStopBreak implements PlayerBlockAction
{
	public function getActionType() : int
	{
		return PlayerActionPacket::ACTION_STOP_BREAK;
	}

	public function write(NetworkBinaryStream $out) : void
	{
		//NOOP
	}
}
