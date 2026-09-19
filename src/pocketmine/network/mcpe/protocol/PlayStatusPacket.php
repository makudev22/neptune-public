<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class PlayStatusPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::PLAY_STATUS_PACKET;

	public const LOGIN_SUCCESS = 0;
	public const LOGIN_FAILED_CLIENT = 1;
	public const LOGIN_FAILED_SERVER = 2;
	public const PLAYER_SPAWN = 3;
	public const LOGIN_FAILED_INVALID_TENANT = 4;
	public const LOGIN_FAILED_VANILLA_EDU = 5;
	public const LOGIN_FAILED_EDU_VANILLA = 6;
	public const LOGIN_FAILED_SERVER_FULL = 7;
	public const LOGIN_FAILED_EDITOR_VANILLA = 8;
	public const LOGIN_FAILED_VANILLA_EDITOR = 9;

	/** @var int */
	public $status;

	protected function decodePayload() : void
	{
		$this->status = $this->getInt();
	}

	public function canBeSentBeforeLogin() : bool
	{
		return true;
	}

	protected function encodePayload() : void
	{
		$this->putInt($this->status);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handlePlayStatus($this);
	}
}
