<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\StructureEditorData;

class StructureBlockUpdatePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::STRUCTURE_BLOCK_UPDATE_PACKET;

	/** @var int */
	public $x;
	/** @var int */
	public $y;
	/** @var int */
	public $z;
	/** @var StructureEditorData */
	public $structureEditorData;
	/** @var bool */
	public $isPowered;
	/** @var bool */
	public $waterlogged;

	protected function decodePayload() : void
	{
		$this->getBlockPosition($this->x, $this->y, $this->z);
		$this->structureEditorData = $this->getStructureEditorData();
		$this->isPowered = $this->getBool();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_554) {
			$this->waterlogged = $this->getBool();
		}
	}

	protected function encodePayload() : void
	{
		$this->putBlockPosition($this->x, $this->y, $this->z);
		$this->putStructureEditorData($this->structureEditorData);
		$this->putBool($this->isPowered);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_554) {
			$this->putBool($this->waterlogged);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleStructureBlockUpdate($this);
	}
}
