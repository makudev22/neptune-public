<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ScriptMessagePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SCRIPT_MESSAGE_PACKET;

	private string $messageId;
	private string $value;

	/**
	 * @generate-create-func
	 */
	public static function create(string $messageId, string $value) : self
	{
		$result = new self();
		$result->messageId = $messageId;
		$result->value = $value;
		return $result;
	}

	public function getMessageId() : string
	{
		return $this->messageId;
	}

	public function getValue() : string
	{
		return $this->value;
	}

	protected function decodePayload() : void
	{
		$this->messageId = $this->getString();
		$this->value = $this->getString();
	}

	protected function encodePayload() : void
	{
		$this->putString($this->messageId);
		$this->putString($this->value);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleScriptMessage($this);
	}
}
