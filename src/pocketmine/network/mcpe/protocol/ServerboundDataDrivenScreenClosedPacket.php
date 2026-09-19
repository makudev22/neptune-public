<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ServerboundDataDrivenScreenClosedPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SERVERBOUND_DATA_DRIVEN_SCREEN_CLOSED_PACKET;

	public const CLOSE_REASON_PROGRAMMATIC_CLOSE = 0;
	public const CLOSE_REASON_PROGRAMMATIC_CLOSE_ALL = 1;
	public const CLOSE_REASON_CLIENT_CANCELED = 2;
	public const CLOSE_REASON_USER_BUSY = 3;
	public const CLOSE_REASON_INVALID_FORM = 4;

	public ?int $formId = null;
	public int $closeReason = self::CLOSE_REASON_PROGRAMMATIC_CLOSE;

	/**
	 * @generate-create-func
	 */
	public static function create(?int $formId, int $closeReason) : self{
		$result = new self();
		$result->formId = $formId;
		$result->closeReason = $closeReason;
		return $result;
	}

	protected function decodePayload() : void{
		$this->formId = $this->getOptional($this->getLInt(...));
		$this->closeReason = $this->getByte();
	}

	protected function encodePayload() : void{
		$this->putOptional($this->formId, $this->putLInt(...));
		$this->putByte($this->closeReason);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleServerboundDataDrivenScreenClosed($this);
	}
}
