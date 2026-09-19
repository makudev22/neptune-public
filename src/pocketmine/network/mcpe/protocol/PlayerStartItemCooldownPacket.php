<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class PlayerStartItemCooldownPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::PLAYER_START_ITEM_COOLDOWN_PACKET;

	private string $itemCategory;
	private int $cooldownTicks;

	/**
	 * @generate-create-func
	 */
	public static function create(string $itemCategory, int $cooldownTicks) : self
	{
		$result = new self();
		$result->itemCategory = $itemCategory;
		$result->cooldownTicks = $cooldownTicks;
		return $result;
	}

	public function getItemCategory() : string
	{
		return $this->itemCategory;
	}

	public function getCooldownTicks() : int
	{
		return $this->cooldownTicks;
	}

	protected function decodePayload() : void
	{
		$this->itemCategory = $this->getString();
		$this->cooldownTicks = $this->getVarInt();
	}

	protected function encodePayload() : void
	{
		$this->putString($this->itemCategory);
		$this->putVarInt($this->cooldownTicks);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handlePlayerStartItemCooldown($this);
	}
}
