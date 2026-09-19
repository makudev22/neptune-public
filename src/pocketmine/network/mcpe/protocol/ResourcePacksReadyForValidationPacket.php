<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ResourcePacksReadyForValidationPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::RESOURCE_PACKS_READY_FOR_VALIDATION_PACKET;

	/**
	 * @generate-create-func
	 */
	public static function create() : self{
		return new self();
	}

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
		return $session->handleResourcePacksReadyForValidation($this);
	}
}
