<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\entity\Attribute;
use pocketmine\network\mcpe\NetworkSession;

class UpdateAttributesPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::UPDATE_ATTRIBUTES_PACKET;

	/** @var int */
	public $entityRuntimeId;
	/** @var Attribute[] */
	public $entries = [];
	/** @var int */
	public $tick = 0;

	protected function decodePayload() : void
	{
		$this->entityRuntimeId = $this->getEntityRuntimeId();
		$this->entries = $this->getAttributeList();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_419) {
			$this->tick = $this->getUnsignedVarLong();
		}
	}

	protected function encodePayload() : void
	{
		$this->putEntityRuntimeId($this->entityRuntimeId);
		$this->putAttributeList(...$this->entries);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_419) {
			$this->putUnsignedVarLong($this->tick);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleUpdateAttributes($this);
	}
}
