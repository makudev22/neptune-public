<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\network\mcpe\NetworkSession;

class NpcDialoguePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::NPC_DIALOGUE_PACKET;

	public const int ACTION_OPEN = 0;
	public const int ACTION_CLOSE = 1;

	public int $npcActorUniqueId;
	public int $actionType;
	public string $dialogue;
	public string $sceneName;
	public string $npcName;
	public string $actionJson;

	public static function create(int $npcActorUniqueId, int $actionType, string $dialogue, string $sceneName, string $npcName, string $actionJson) : self
	{
		$result = new self();
		$result->npcActorUniqueId = $npcActorUniqueId;
		$result->actionType = $actionType;
		$result->dialogue = $dialogue;
		$result->sceneName = $sceneName;
		$result->npcName = $npcName;
		$result->actionJson = $actionJson;
		return $result;
	}

	public function getNpcActorUniqueId() : int
	{
		return $this->npcActorUniqueId;
	}

	public function getActionType() : int
	{
		return $this->actionType;
	}

	public function getDialogue() : string
	{
		return $this->dialogue;
	}

	public function getSceneName() : string
	{
		return $this->sceneName;
	}

	public function getNpcName() : string
	{
		return $this->npcName;
	}

	public function getActionJson() : string
	{
		return $this->actionJson;
	}

	protected function decodePayload() : void
	{
		$this->npcActorUniqueId = $this->getEntityUniqueId();
		$this->actionType = $this->getVarInt();
		$this->dialogue = $this->getString();
		$this->sceneName = $this->getString();
		$this->npcName = $this->getString();
		$this->actionJson = $this->getString();
	}

	protected function encodePayload() : void
	{
		$this->putEntityUniqueId($this->npcActorUniqueId);
		$this->putVarInt($this->actionType);
		$this->putString($this->dialogue);
		$this->putString($this->sceneName);
		$this->putString($this->npcName);
		$this->putString($this->actionJson);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleNpcDialogue($this);
	}
}
