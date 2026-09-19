<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

/**
 * Sent by the server to open the sign GUI for a sign.
 */
class OpenSignPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::OPEN_SIGN_PACKET;

	/** @var int */
	public $x;
	/** @var int */
	public $y;
	/** @var int */
	public $z;
	/** @var bool */
	public $frontSide;

	protected function decodePayload() : void
	{
		$this->getBlockPosition($this->x, $this->y, $this->z);
		$this->frontSide = $this->getBool();
	}

	protected function encodePayload() : void
	{
		$this->putBlockPosition($this->x, $this->y, $this->z);
		$this->putBool($this->frontSide);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleOpenSign($this);
	}
}
