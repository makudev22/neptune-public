<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class CompletedUsingItemPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::COMPLETED_USING_ITEM_PACKET;

	public const ACTION_UNKNOWN = -1;
	public const ACTION_EQUIP_ARMOR = 0;
	public const ACTION_EAT = 1;
	public const ACTION_ATTACK = 2;
	public const ACTION_CONSUME = 3;
	public const ACTION_THROW = 4;
	public const ACTION_SHOOT = 5;
	public const ACTION_PLACE = 6;
	public const ACTION_FILL_BOTTLE = 7;
	public const ACTION_FILL_BUCKET = 8;
	public const ACTION_POUR_BUCKET = 9;
	public const ACTION_USE_TOOL = 10;
	public const ACTION_INTERACT = 11;
	public const ACTION_RETRIEVED = 12;
	public const ACTION_DYED = 13;
	public const ACTION_TRADED = 14;
	public const ACTION_BRUSHING_COMPLETED = 15;

	public int $itemId;
	public int $action;

	protected function decodePayload() : void
	{
		$this->itemId = $this->getShort();
		$this->action = $this->getLInt();
	}

	protected function encodePayload() : void
	{
		$this->putShort($this->itemId);
		$this->putLInt($this->action);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleCompletedUsingItem($this);
	}
}
