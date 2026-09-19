<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class PhotoTransferPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::PHOTO_TRANSFER_PACKET;

	/** @var string */
	public $photoName;
	/** @var string */
	public $photoData;
	/** @var string */
	public $bookId; //photos are stored in a sibling directory to the games folder (screenshots/(some UUID)/bookID/example.png)
	/** @var int */
	public $type;
	/** @var int */
	public $sourceType;
	/** @var int */
	public $ownerEntityUniqueId;
	/** @var string */
	public $newPhotoName; //???

	protected function decodePayload() : void
	{
		$this->photoName = $this->getString();
		$this->photoData = $this->getString();
		$this->bookId = $this->getString();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_465) {
			$this->type = $this->getByte();
			$this->sourceType = $this->getByte();
			$this->ownerEntityUniqueId = $this->getLLong(); //...............
			$this->newPhotoName = $this->getString();
		}
	}

	protected function encodePayload() : void
	{
		$this->putString($this->photoName);
		$this->putString($this->photoData);
		$this->putString($this->bookId);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_465) {
			$this->putByte($this->type);
			$this->putByte($this->sourceType);
			$this->putLLong($this->ownerEntityUniqueId);
			$this->putString($this->newPhotoName);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handlePhotoTransfer($this);
	}
}
