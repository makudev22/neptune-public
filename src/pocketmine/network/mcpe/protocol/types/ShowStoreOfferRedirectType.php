<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

enum ShowStoreOfferRedirectType : int
{
	use PacketIntEnumTrait;

	case MARKETPLACE = 0;
	case DRESSING_ROOM = 1;
	case THIRD_PARTY_SERVER_PAGE = 2;
}
