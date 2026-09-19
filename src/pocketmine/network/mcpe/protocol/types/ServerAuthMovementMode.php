<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

enum ServerAuthMovementMode : int
{
	use PacketIntEnumTrait;

	case LEGACY_CLIENT_AUTHORITATIVE_V1 = 0; //MovePlayerPacket (only max 1.21.70)
	case SERVER_AUTHORITATIVE_V2 = 1; //PlayerAuthInputPacket
	case SERVER_AUTHORITATIVE_V3 = 2; //PlayerAuthInputPacket + a bunch of junk that solves a nonexisting problem
}
