<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\network\mcpe\NetworkSession;

class CreatePhotoPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CREATE_PHOTO_PACKET;

	private int $entityUniqueId;
	private string $photoName;
	private string $photoItemName;

	public static function create(int $actorUniqueId, string $photoName, string $photoItemName) : self
	{
		$result = new self();
		$result->entityUniqueId = $actorUniqueId;
		$result->photoName = $photoName;
		$result->photoItemName = $photoItemName;
		return $result;
	}

	/**
	 * TODO: rename this to getEntityUniqueId() on PM4 (shit architecture, thanks shoghi)
	 */
	public function getEntityUniqueIdField() : int
	{
		return $this->entityUniqueId;
	}

	public function getPhotoName() : string
	{
		return $this->photoName;
	}

	public function getPhotoItemName() : string
	{
		return $this->photoItemName;
	}

	protected function decodePayload() : void
	{
		$this->entityUniqueId = $this->getLLong(); //why be consistent mojang ?????
		$this->photoName = $this->getString();
		$this->photoItemName = $this->getString();
	}

	protected function encodePayload() : void
	{
		$this->putLLong($this->entityUniqueId);
		$this->putString($this->photoName);
		$this->putString($this->photoItemName);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleCreatePhoto($this);
	}
}
