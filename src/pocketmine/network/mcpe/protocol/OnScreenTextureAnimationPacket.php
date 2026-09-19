<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class OnScreenTextureAnimationPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::ON_SCREEN_TEXTURE_ANIMATION_PACKET;

	/** @var int */
	public $effectId;

	protected function decodePayload() : void
	{
		$this->effectId = $this->getLInt(); //unsigned
	}

	protected function encodePayload() : void
	{
		$this->putLInt($this->effectId);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleOnScreenTextureAnimation($this);
	}
}
