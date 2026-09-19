<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class AddBehaviorTreePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::ADD_BEHAVIOR_TREE_PACKET;

	public string $behaviorTreeJson;

	/**
	 * @generate-create-func
	 */
	public static function create(string $behaviorTreeJson) : self
	{
		$result = new self();
		$result->behaviorTreeJson = $behaviorTreeJson;
		return $result;
	}

	protected function decodePayload() : void
	{
		$this->behaviorTreeJson = $this->getString();
	}

	protected function encodePayload() : void
	{
		$this->putString($this->behaviorTreeJson);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleAddBehaviorTree($this);
	}
}
