<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class PlayerInputPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::PLAYER_INPUT_PACKET;

	/** @var float */
	public $motionX;
	/** @var float */
	public $motionY;
	/** @var bool */
	public $jumping;
	/** @var bool */
	public $sneaking;

	protected function decodePayload() : void
	{
		$this->motionX = $this->getLFloat();
		$this->motionY = $this->getLFloat();
		$this->jumping = $this->getBool();
		$this->sneaking = $this->getBool();
	}

	protected function encodePayload() : void
	{
		$this->putLFloat($this->motionX);
		$this->putLFloat($this->motionY);
		$this->putBool($this->jumping);
		$this->putBool($this->sneaking);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handlePlayerInput($this);
	}
}
