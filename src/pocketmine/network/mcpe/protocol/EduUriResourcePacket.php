<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\EducationUriResource;

class EduUriResourcePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::EDU_URI_RESOURCE_PACKET;

	private EducationUriResource $resource;

	public static function create(EducationUriResource $resource) : self
	{
		$result = new self();
		$result->resource = $resource;
		return $result;
	}

	public function getResource() : EducationUriResource
	{
		return $this->resource;
	}

	protected function decodePayload() : void
	{
		$this->resource = EducationUriResource::read($this);
	}

	protected function encodePayload() : void
	{
		$this->resource->write($this);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleEduUriResource($this);
	}
}
