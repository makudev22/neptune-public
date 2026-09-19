<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\camera\CameraPreset;

use function count;

class CameraPresetsPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CAMERA_PRESETS_PACKET;

	/** @var CameraPreset[] */
	public array $presets = [];

	/** @phpstan-var CompoundTag */
	public CompoundTag $data; //old

	protected function decodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_618) {
			$this->presets = [];
			for ($i = 0, $count = $this->getUnsignedVarInt(); $i < $count; $i++) {
				$this->presets[] = CameraPreset::read($this);
			}
		} else {
			$this->data = $this->getNbtCompoundRoot();
		}
	}

	protected function encodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_618) {
			$this->putUnsignedVarInt(count($this->presets));
			foreach ($this->presets as $preset) {
				$preset->write($this);
			}
		} else {
			$this->put((new NetworkLittleEndianNBTStream())->write($this->data));
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleCameraPresets($this);
	}
}
