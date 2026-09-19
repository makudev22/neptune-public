<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class UpdateBlockSyncedPacket extends UpdateBlockPacket
{
	public const NETWORK_ID = ProtocolInfo::UPDATE_BLOCK_SYNCED_PACKET;

	/** @var int */
	public $entityUniqueId = 0;
	/** @var int */
	public $uvarint64_2 = 0;

	protected function decodePayload() : void
	{
		parent::decodePayload();
		$this->entityUniqueId = $this->getUnsignedVarLong();
		$this->uvarint64_2 = $this->getUnsignedVarLong();
	}

	protected function encodePayload() : void
	{
		parent::encodePayload();
		$this->putUnsignedVarLong($this->entityUniqueId);
		$this->putUnsignedVarLong($this->uvarint64_2);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleUpdateBlockSynced($this);
	}
}
