<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ModalFormResponsePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::MODAL_FORM_RESPONSE_PACKET;

	public const CANCEL_REASON_CLOSED = 0;
	/** Sent if a form is sent when the player is on a loading screen */
	public const CANCEL_REASON_USER_BUSY = 1;

	/** @var int */
	public $formId;
	/** @var ?string */
	public $formData; //json
	/** @var ?int */
	public $cancelReason;

	protected function decodePayload() : void
	{
		$this->formId = $this->getUnsignedVarInt();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_544) {
			$this->formData = $this->getBool() ? $this->getString() : null;
			$this->cancelReason = $this->getBool() ? $this->getByte() : null;
		} else {
			$this->formData = $this->getString();
		}
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt($this->formId);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_544) {
			if ($this->formData !== null) {
				$this->putBool(true);
				$this->putString($this->formData);
			} else {
				$this->putBool(false);
			}
			if ($this->cancelReason !== null) {
				$this->putBool(true);
				$this->putByte($this->cancelReason);
			} else {
				$this->putBool(false);
			}
		} else {
			$this->putString($this->formData);
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleModalFormResponse($this);
	}
}
