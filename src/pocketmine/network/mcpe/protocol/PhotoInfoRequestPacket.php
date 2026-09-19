<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\network\mcpe\NetworkSession;

class PhotoInfoRequestPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::PHOTO_INFO_REQUEST_PACKET;

	private int $photoId;

	public static function create(int $photoId) : self
	{
		$result = new self();
		$result->photoId = $photoId;
		return $result;
	}

	protected function decodePayload() : void
	{
		$this->photoId = $this->getEntityUniqueId();
	}

	protected function encodePayload() : void
	{
		$this->putEntityUniqueId($this->photoId);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handlePhotoInfoRequest($this);
	}
}
