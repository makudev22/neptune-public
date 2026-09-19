<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class GuiDataPickItemPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::GUI_DATA_PICK_ITEM_PACKET;

	/** @var string */
	public $itemDescription;
	/** @var string */
	public $itemEffects;
	/** @var int */
	public $hotbarSlot;

	protected function decodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->itemDescription = $this->getString();
			$this->itemEffects = $this->getString();
		}
		$this->hotbarSlot = $this->getLInt();
	}

	protected function encodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->putString($this->itemDescription);
			$this->putString($this->itemEffects);
		}
		$this->putLInt($this->hotbarSlot);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleGuiDataPickItem($this);
	}
}
