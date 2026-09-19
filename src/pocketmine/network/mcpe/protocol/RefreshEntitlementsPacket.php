<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class RefreshEntitlementsPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::REFRESH_ENTITLEMENTS_PACKET;

	/**
	 * @generate-create-func
	 */
	public static function create() : self
	{
		return new self();
	}

	protected function decodePayload() : void
	{
		//NOOP
	}

	protected function encodePayload() : void
	{
		//NOOP
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleRefreshEntitlements($this);
	}
}
