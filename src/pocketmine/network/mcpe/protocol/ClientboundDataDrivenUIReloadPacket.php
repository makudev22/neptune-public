<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ClientboundDataDrivenUIReloadPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_DATA_DRIVEN_UI_RELOAD_PACKET;

	protected function decodePayload() : void{

	}

	protected function encodePayload() : void{

	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleClientboundDataDrivenUIReload($this);
	}
}
