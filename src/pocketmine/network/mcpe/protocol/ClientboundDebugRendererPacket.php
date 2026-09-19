<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\DebugMarkerData;
use pocketmine\network\mcpe\protocol\types\DebugRendererType;

class ClientboundDebugRendererPacket extends DataPacket {
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_DEBUG_RENDERER_PACKET;

	private DebugRendererType $type;
	private ?DebugMarkerData $data = null;

	protected function decodePayload() : void{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_897) {
			$this->type = DebugRendererType::fromName($this->getString());
			$this->data = $this->getOptional(fn(NetworkBinaryStream $in) => DebugMarkerData::read($in));
		} else {
			$this->type = DebugRendererType::fromPacket($this->getLInt());
			$this->data = DebugMarkerData::read($this);
		}
	}

	protected function encodePayload() : void{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_897) {
			$this->putString($this->type->getName());
			$this->putOptional($this->data, fn(DebugMarkerData $data) => $data->write($this));
		} else {
			$this->putLInt($this->type->value);
			$this->data->write($this);
		}
	}

	public function mustBeDecoded() : bool{
		return false;
	}

	public function handle(NetworkSession $session) : bool{
		return $session->handleClientboundDebugRenderer($this);
	}
}
