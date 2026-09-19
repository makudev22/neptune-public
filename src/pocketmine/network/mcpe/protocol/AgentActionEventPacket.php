<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\AgentActionType;

/**
 * Used by code builder, exact purpose unclear
 */
class AgentActionEventPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::AGENT_ACTION_EVENT_PACKET;

	private string $requestId;
	/** @see AgentActionType */
	private int $action;
	private string $responseJson;

	/**
	 * @generate-create-func
	 */
	public static function create(string $requestId, int $action, string $responseJson) : self
	{
		$result = new self();
		$result->requestId = $requestId;
		$result->action = $action;
		$result->responseJson = $responseJson;
		return $result;
	}

	public function getRequestId() : string
	{
		return $this->requestId;
	}

	/** @see AgentActionType */
	public function getAction() : int
	{
		return $this->action;
	}

	public function getResponseJson() : string
	{
		return $this->responseJson;
	}

	protected function decodePayload() : void
	{
		$this->requestId = $this->getString();
		$this->action = $this->getLInt();
		$this->responseJson = $this->getString();
	}

	protected function encodePayload() : void
	{
		$this->putString($this->requestId);
		$this->putLInt($this->action);
		$this->putString($this->responseJson);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleAgentActionEvent($this);
	}
}
