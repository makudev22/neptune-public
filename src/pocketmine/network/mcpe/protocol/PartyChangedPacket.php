<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class PartyChangedPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::PARTY_CHANGED_PACKET;

	private string $partyId = "";
	private bool $partyLeader = false;

	/**
	 * @generate-create-func
	 */
	public static function create(string $partyId, bool $partyLeader) : self{
		$result = new self();
		$result->partyId = $partyId;
		$result->partyLeader = $partyLeader;
		return $result;
	}

	public function getPartyId() : string{ return $this->partyId; }

	public function isPartyLeader() : bool{ return $this->partyLeader; }

	protected function decodePayload() : void{
		$this->partyId = $this->getString();
		if ($this->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$this->partyLeader = $this->getBool();
		}
	}

	protected function encodePayload() : void{
		$this->putString($this->partyId);
		if ($this->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$this->putBool($this->partyLeader);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handlePartyChanged($this);
	}
}
