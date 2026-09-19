<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\entity\Skin;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\utils\UUID;

class PlayerSkinPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::PLAYER_SKIN_PACKET;

	public UUID $uuid;
	public string $oldSkinName = "";
	public string $newSkinName = "";
	public ?Skin $skin = null;

	protected function decodePayload() : void{
		$this->uuid = $this->getUUID();
		$this->skin = $this->getSkin();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->newSkinName = $this->getString();
			$this->oldSkinName = $this->getString();
		} else {
			$this->newSkinName = $this->getString();
			$this->oldSkinName = $this->getString();
			$this->getBool();
		}
	}

	protected function encodePayload() : void{
		$this->putUUID($this->uuid);
		$this->putSkin($this->skin);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->putString($this->newSkinName);
			$this->putString($this->oldSkinName);
		} else {
			$this->putString($this->newSkinName);
			$this->putString($this->oldSkinName);
			$this->putBool($this->skin->getSerializedSkin()->isTrustedSkin());
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handlePlayerSkin($this);
	}
}
