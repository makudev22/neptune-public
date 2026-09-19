<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class GameRulesChangedPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::GAME_RULES_CHANGED_PACKET;

	public array $gameRules = [];

	protected function decodePayload() : void
	{
		$this->gameRules = $this->getGameRules(false);
	}

	protected function encodePayload() : void
	{
		$this->putGameRules($this->gameRules, false);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleGameRulesChanged($this);
	}
}
