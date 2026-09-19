<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class EmotePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::EMOTE_PACKET;

	public const FLAG_SERVER = 1 << 0;
	public const FLAG_MUTE_ANNOUNCEMENT = 1 << 1;

	public int $entityRuntimeId;
	public string $emoteId;
	public int $emoteLengthTicks = 0;
	public string $xboxUserId;
	public string $platformChatId;
	public int $flags;

	/**
	 * @generate-create-func
	 */
	public static function create(int $entityRuntimeId, string $emoteId, int $emoteLengthTicks, string $xboxUserId, string $platformChatId, int $flags) : self
	{
		$result = new self();
		$result->entityRuntimeId = $entityRuntimeId;
		$result->emoteId = $emoteId;
		$result->emoteLengthTicks = $emoteLengthTicks;
		$result->xboxUserId = $xboxUserId;
		$result->platformChatId = $platformChatId;
		$result->flags = $flags;
		return $result;
	}

	/**
	 * TODO: we can't call this getEntityRuntimeId() because of base class collision (crap architecture, thanks Shoghi)
	 */
	public function getEntityRuntimeIdField() : int
	{
		return $this->entityRuntimeId;
	}

	public function getEmoteId() : string
	{
		return $this->emoteId;
	}

	public function getEmoteLengthTicks() : int
	{
		return $this->emoteLengthTicks;
	}

	public function getXboxUserId() : string
	{
		return $this->xboxUserId;
	}

	public function getPlatformChatId() : string
	{
		return $this->platformChatId;
	}

	public function getFlags() : int
	{
		return $this->flags;
	}

	protected function decodePayload() : void
	{
		$this->entityRuntimeId = $this->getEntityRuntimeId();
		$this->emoteId = $this->getString();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_589) {
			if ($this->protocol >= ProtocolInfo::PROTOCOL_729) {
				$this->emoteLengthTicks = $this->getUnsignedVarInt();
			}
			$this->xboxUserId = $this->getString();
			$this->platformChatId = $this->getString();
		}
		$this->flags = $this->getByte();
	}

	protected function encodePayload() : void
	{
		$this->putEntityRuntimeId($this->entityRuntimeId);
		$this->putString($this->emoteId);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_589) {
			if ($this->protocol >= ProtocolInfo::PROTOCOL_729) {
				$this->putUnsignedVarInt($this->emoteLengthTicks);
			}
			$this->putString($this->xboxUserId);
			$this->putString($this->platformChatId);
		}
		$this->putByte($this->flags);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleEmote($this);
	}
}
