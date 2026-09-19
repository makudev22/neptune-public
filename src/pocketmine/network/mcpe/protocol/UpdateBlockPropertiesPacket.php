<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\NetworkSession;

class UpdateBlockPropertiesPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::UPDATE_BLOCK_PROPERTIES_PACKET;

	public string $nbt;

	public static function create(CompoundTag $data) : self
	{
		$result = new self();
		$result->nbt = (new NetworkLittleEndianNBTStream())->write($data);
		return $result;
	}

	protected function decodePayload() : void
	{
		$this->nbt = $this->getRemaining();
	}

	protected function encodePayload() : void
	{
		$this->put($this->nbt);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleUpdateBlockProperties($this);
	}
}
