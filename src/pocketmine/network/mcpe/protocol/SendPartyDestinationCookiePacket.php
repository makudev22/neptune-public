<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class SendPartyDestinationCookiePacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::SEND_PARTY_DESTINATION_COOKIE_PACKET;

	private string $cookie;
	private string $intent;
	private string $destinationName;

	/**
	 * @generate-create-func
	 */
	public static function create(string $cookie, string $intent, string $destinationName) : self{
		$result = new self();
		$result->cookie = $cookie;
		$result->intent = $intent;
		$result->destinationName = $destinationName;
		return $result;
	}

	public function getCookie() : string{ return $this->cookie; }

	public function getIntent() : string{ return $this->intent; }

	public function getDestinationName() : string{ return $this->destinationName; }

	protected function decodePayload() : void{
		$this->cookie = $this->getString();
		$this->intent = $this->getString();
		$this->destinationName = $this->getString();
	}

	protected function encodePayload() : void{
		$this->putString($this->cookie);
		$this->putString($this->intent);
		$this->putString($this->destinationName);
	}

	public function handle(NetworkSession $session) : bool{
		return $session->handleSendPartyDestinationCookie($this);
	}
}
