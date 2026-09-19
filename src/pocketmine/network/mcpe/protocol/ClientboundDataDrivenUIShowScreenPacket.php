<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ClientboundDataDrivenUIShowScreenPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_DATA_DRIVEN_UI_SHOW_SCREEN_PACKET;

	public string $screenId;
	public int $formId;
	public ?int $dataInstanceId = null;

	protected function decodePayload() : void{
		$this->screenId = $this->getString();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_944) {
			$this->formId = $this->getLInt();
			$this->dataInstanceId = $this->getOptional(fn() => $this->getLInt());
		}
	}

	protected function encodePayload() : void{
		$this->putString($this->screenId);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_944) {
			$this->putLInt($this->formId);
			$this->putOptional($this->dataInstanceId, fn(int $dataInstanceId) => $this->putLInt($dataInstanceId));
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleClientboundDataDrivenUIShowScreen($this);
	}
}
