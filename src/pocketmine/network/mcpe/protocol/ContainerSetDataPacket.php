<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ContainerSetDataPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CONTAINER_SET_DATA_PACKET;

	public const PROPERTY_FURNACE_TICK_COUNT = 0;
	public const PROPERTY_FURNACE_LIT_TIME = 1;
	public const PROPERTY_FURNACE_LIT_DURATION = 2;
	public const PROPERTY_FURNACE_STORED_XP = 3;
	public const PROPERTY_FURNACE_FUEL_AUX = 4;

	public const PROPERTY_BREWING_STAND_BREW_TIME = 0;
	public const PROPERTY_BREWING_STAND_FUEL_AMOUNT = 1;
	public const PROPERTY_BREWING_STAND_FUEL_TOTAL = 2;

	public int $windowId;
	public int $property;
	public int $value;

	protected function decodePayload() : void
	{
		$this->windowId = $this->getByte();
		$this->property = $this->getVarInt();
		$this->value = $this->getVarInt();
	}

	protected function encodePayload() : void
	{
		$this->putByte($this->windowId);
		$this->putVarInt($this->property);
		$this->putVarInt($this->value);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleContainerSetData($this);
	}
}
