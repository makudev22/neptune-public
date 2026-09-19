<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class VideoStreamConnectPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::VIDEO_STREAM_CONNECT_PACKET;

	public const ACTION_CONNECT = 0;
	public const ACTION_DISCONNECT = 1;

	/** @var string */
	public $serverUri;
	/** @var float */
	public $frameSendFrequency;
	/** @var int */
	public $action;
	/** @var int */
	public $resolutionX;
	/** @var int */
	public $resolutionY;

	protected function decodePayload() : void
	{
		$this->serverUri = $this->getString();
		$this->frameSendFrequency = $this->getLFloat();
		$this->action = $this->getByte();
		$this->resolutionX = $this->getLInt();
		$this->resolutionY = $this->getLInt();
	}

	protected function encodePayload() : void
	{
		$this->putString($this->serverUri);
		$this->putLFloat($this->frameSendFrequency);
		$this->putByte($this->action);
		$this->putLInt($this->resolutionX);
		$this->putLInt($this->resolutionY);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleVideoStreamConnect($this);
	}
}
