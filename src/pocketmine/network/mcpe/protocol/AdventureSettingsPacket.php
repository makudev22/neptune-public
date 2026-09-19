<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\AdventureSettingsData;

class AdventureSettingsPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::ADVENTURE_SETTINGS_PACKET;

	public AdventureSettingsData $adventureSettingsData;

	/**
	 * @generate-create-func
	 */
	public static function create(AdventureSettingsData $adventureSettingsData) : self
	{
		$result = new self();
		$result->adventureSettingsData = $adventureSettingsData;
		return $result;
	}

	public function getAdventureSettingsData() : AdventureSettingsData {
		return $this->adventureSettingsData;
	}

	protected function decodePayload() : void
	{
		$this->adventureSettingsData = AdventureSettingsData::decode($this);
	}

	protected function encodePayload() : void
	{
		$this->adventureSettingsData->encode($this);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleAdventureSettings($this);
	}
}
