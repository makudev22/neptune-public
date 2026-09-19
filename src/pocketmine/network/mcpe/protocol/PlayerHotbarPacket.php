<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\inventory\ContainerIds;

use function count;

/**
 * One of the most useless packets.
 */
class PlayerHotbarPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::PLAYER_HOTBAR_PACKET;

	/** @var int */
	public $selectedHotbarSlot;
	/** @var int */
	public $windowId = ContainerIds::INVENTORY;
	/** @var array */
	public $slots = [];
	/** @var bool */
	public $selectHotbarSlot = true;

	protected function decodePayload() : void
	{
		$this->selectedHotbarSlot = $this->getUnsignedVarInt();
		$this->windowId = $this->getByte();
		if ($this->protocol <= ProtocolInfo::PROTOCOL_407) {
			$slots = $this->getUnsignedVarInt();
			for ($slot = 0; $slot < $slots; ++$slot) {
				$this->slots[$slot] = $this->getUnsignedVarInt();
			}
		}
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->selectHotbarSlot = $this->getBool();
		}
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt($this->selectedHotbarSlot);
		$this->putByte($this->windowId);
		if ($this->protocol <= ProtocolInfo::PROTOCOL_407) {
			$this->putUnsignedVarInt(count($this->slots));
			foreach ($this->slots as $slot) {
				$this->putUnsignedVarInt($slot);
			}
		}
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->putBool($this->selectHotbarSlot);
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handlePlayerHotbar($this);
	}
}
