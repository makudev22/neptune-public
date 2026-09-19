<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class StructureTemplateDataResponsePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::STRUCTURE_TEMPLATE_DATA_RESPONSE_PACKET;

	/** @var string */
	public $structureTemplateName;
	/** @var string|null */
	public $namedtag;

	protected function decodePayload() : void
	{
		$this->structureTemplateName = $this->getString();
		if ($this->getBool()) {
			$this->namedtag = $this->getRemaining();
		}
	}

	protected function encodePayload() : void
	{
		$this->putString($this->structureTemplateName);
		$this->putBool($this->namedtag !== null);
		if ($this->namedtag !== null) {
			$this->put($this->namedtag);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleStructureTemplateDataResponse($this);
	}
}
