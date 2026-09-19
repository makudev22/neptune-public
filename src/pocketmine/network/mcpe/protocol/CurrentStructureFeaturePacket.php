<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class CurrentStructureFeaturePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CURRENT_STRUCTURE_FEATURE_PACKET;

	public string $currentStructureFeature;

	/**
	 * @generate-create-func
	 */
	public static function create(string $currentStructureFeature) : self
	{
		$result = new self();
		$result->currentStructureFeature = $currentStructureFeature;
		return $result;
	}

	protected function decodePayload() : void
	{
		$this->currentStructureFeature = $this->getString();
	}

	protected function encodePayload() : void
	{
		$this->putString($this->currentStructureFeature);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleCurrentStructureFeature($this);
	}
}
