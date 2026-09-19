<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class FilterTextPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::FILTER_TEXT_PACKET;

	public string $text;
	public bool $fromServer;

	public static function create(string $text, bool $server) : self
	{
		$result = new self();
		$result->text = $text;
		$result->fromServer = $server;
		return $result;
	}

	public function getText() : string
	{
		return $this->text;
	}

	public function isFromServer() : bool
	{
		return $this->fromServer;
	}

	protected function decodePayload() : void
	{
		$this->text = $this->getString();
		$this->fromServer = $this->getBool();
	}

	protected function encodePayload() : void
	{
		$this->putString($this->text);
		$this->putBool($this->fromServer);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleFilterText($this);
	}
}
