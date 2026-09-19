<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class AnvilDamagePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::ANVIL_DAMAGE_PACKET;

	public int $x;
	public int $y;
	public int $z;
	public int $damageAmount = 0;

	public static function create(int $x, int $y, int $z, int $damageAmount) : self
	{
		$result = new self();
		[$result->x, $result->y, $result->z] = [$x, $y, $z];
		$result->damageAmount = $damageAmount;
		return $result;
	}

	public function getDamageAmount() : int
	{
		return $this->damageAmount;
	}

	public function getX() : int
	{
		return $this->x;
	}

	public function getY() : int
	{
		return $this->y;
	}

	public function getZ() : int
	{
		return $this->z;
	}

	protected function decodePayload() : void
	{
		if ($this->protocol < ProtocolInfo::PROTOCOL_2193) {
			$this->damageAmount = $this->getByte();
		}
		$this->getBlockPosition($this->x, $this->y, $this->z);
	}

	protected function encodePayload() : void
	{
		if ($this->protocol < ProtocolInfo::PROTOCOL_2193) {
			$this->putByte($this->damageAmount);
		}
		$this->putBlockPosition($this->x, $this->y, $this->z);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleAnvilDamage($this);
	}
}
