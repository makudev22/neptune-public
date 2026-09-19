<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\block\Block;
use pocketmine\network\mcpe\convert\block\RuntimeBlockMapping;
use pocketmine\network\mcpe\NetworkSession;

class UpdateBlockPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::UPDATE_BLOCK_PACKET;

	public const FLAG_NONE = 0b0000;
	public const FLAG_NEIGHBORS = 0b0001;
	public const FLAG_NETWORK = 0b0010;
	public const FLAG_NOGRAPHIC = 0b0100;
	public const FLAG_PRIORITY = 0b1000;

	public const FLAG_ALL = self::FLAG_NEIGHBORS | self::FLAG_NETWORK;
	public const FLAG_ALL_PRIORITY = self::FLAG_ALL | self::FLAG_PRIORITY;

	public const DATA_LAYER_NORMAL = 0;
	public const DATA_LAYER_LIQUID = 1;

	public int $x;
	public int $z;
	public int $y;
	public int $blockId;
	public int $blockMeta;
	public int $flags;
	public int $dataLayerId = self::DATA_LAYER_NORMAL;
	public ?int $runtimeIdOverride = null;

	protected function decodePayload() : void
	{
		$this->getBlockPosition($this->x, $this->y, $this->z);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$fullState = RuntimeBlockMapping::getInstance($this->protocol)->fromRuntimeId($this->getUnsignedVarInt());
			$this->blockId = $fullState >> Block::INTERNAL_METADATA_BITS;
			$this->blockMeta = $fullState & Block::INTERNAL_METADATA_MASK;
			$this->flags = $this->getUnsignedVarInt();
			$this->dataLayerId = $this->getUnsignedVarInt();
		} else {
			$this->blockId = $this->getUnsignedVarInt();
			$aux = $this->getUnsignedVarInt();
			$this->blockMeta = $aux & 0x0f;
			$this->flags = $aux >> 4;
		}
	}

	protected function encodePayload() : void
	{
		$this->putBlockPosition($this->x, $this->y, $this->z);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$runtimeId = $this->runtimeIdOverride ?? RuntimeBlockMapping::getInstance($this->protocol)->toRuntimeId(($this->blockId << Block::INTERNAL_METADATA_BITS) | $this->blockMeta);
			$this->putUnsignedVarInt($runtimeId);
			$this->putUnsignedVarInt($this->flags);
			$this->putUnsignedVarInt($this->dataLayerId);
		} else {
			$this->putUnsignedVarInt($this->blockId);
			$this->putUnsignedVarInt(($this->flags << 4) | $this->blockMeta);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleUpdateBlock($this);
	}
}
