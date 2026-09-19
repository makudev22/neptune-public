<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ServerSettingsResponsePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SERVER_SETTINGS_RESPONSE_PACKET;

	/** @var int */
	public $formId;
	/** @var string */
	public $formData; //json

	protected function decodePayload() : void
	{
		$this->formId = $this->getUnsignedVarInt();
		$this->formData = $this->getString();
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt($this->formId);
		$this->putString($this->formData);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleServerSettingsResponse($this);
	}
}
