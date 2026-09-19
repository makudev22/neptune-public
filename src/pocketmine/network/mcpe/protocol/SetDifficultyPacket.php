<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class SetDifficultyPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SET_DIFFICULTY_PACKET;

	/** @var int */
	public $difficulty;

	protected function decodePayload() : void
	{
		$this->difficulty = $this->getUnsignedVarInt();
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt($this->difficulty);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSetDifficulty($this);
	}
}
