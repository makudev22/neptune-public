<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkSession;

class ChangeDimensionPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CHANGE_DIMENSION_PACKET;

	public int $dimension;
	public Vector3 $position;
	public bool $respawn = false;
	private ?int $loadingScreenId = null;

	protected function decodePayload() : void
	{
		$this->dimension = $this->getVarInt();
		$this->position = $this->getVector3();
		$this->respawn = $this->getBool();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_712) {
			$this->loadingScreenId = $this->getOptional(fn () => $this->getLInt());
		}
	}

	protected function encodePayload() : void
	{
		$this->putVarInt($this->dimension);
		$this->putVector3($this->position);
		$this->putBool($this->respawn);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_712) {
			$this->putOptional($this->loadingScreenId, $this->putLInt(...));
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleChangeDimension($this);
	}
}
