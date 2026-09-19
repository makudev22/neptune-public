<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

use function count;

class ResourcePackClientResponsePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::RESOURCE_PACK_CLIENT_RESPONSE_PACKET;

	public const STATUS_REFUSED = 1;
	public const STATUS_SEND_PACKS = 2;
	public const STATUS_HAVE_ALL_PACKS = 3;
	public const STATUS_COMPLETED = 4;

	/** @var int */
	public $status;
	/** @var string[] */
	public $packIds = [];

	protected function decodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->status = $this->getUnsignedVarInt() + 1;
			$this->getString();
			$this->packIds = [];
			if ($this->status === self::STATUS_SEND_PACKS) {
				$entryCount = $this->getUnsignedVarInt();
				if ($entryCount > 128) {
					throw new PacketDecodeException("Too many entry count in resource pack response: " . $entryCount);
				}
				while ($entryCount-- > 0) {
					$this->packIds[] = $this->getString();
				}
			}
			return;
		}

		$this->status = $this->getByte();
		$entryCount = $this->getLShort();
		if ($entryCount > 128) {
			throw new PacketDecodeException("Too many entry count in resource pack response: " . $entryCount);
		}
		while ($entryCount-- > 0) {
			$this->packIds[] = $this->getString();
		}
	}

	protected function encodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->putUnsignedVarInt($this->status - 1);
			$this->putString(match ($this->status) {
				self::STATUS_REFUSED => "cancel",
				self::STATUS_SEND_PACKS => "downloading",
				self::STATUS_HAVE_ALL_PACKS => "downloadingfinished",
				self::STATUS_COMPLETED => "resourcepackstackfinished",
				default => "",
			});
			if ($this->status === self::STATUS_SEND_PACKS) {
				$this->putUnsignedVarInt(count($this->packIds));
				foreach ($this->packIds as $id) {
					$this->putString($id);
				}
			}
			return;
		}

		$this->putByte($this->status);
		$this->putLShort(count($this->packIds));
		foreach ($this->packIds as $id) {
			$this->putString($id);
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleResourcePackClientResponse($this);
	}
}
