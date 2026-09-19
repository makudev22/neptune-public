<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;

class UseItemPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::USE_ITEM_PACKET;

	public int $x = 0;
	public int $y = 0;
	public int $z = 0;
	public int $blockId;
	public int $face;
	public Vector3 $playerPos;
	public Vector3 $clickPos;
	public int $slot;
	public ItemStack $item;

	protected function decodePayload() : void
	{
		$this->getBlockPosition($this->x, $this->y, $this->z);
		$this->blockId = $this->getUnsignedVarInt();
		$this->face = $this->getVarInt();
		$this->clickPos = $this->getVector3();
		$this->playerPos = $this->getVector3();
		$this->slot = $this->getVarInt();
		$this->item = $this->getItemStackWithoutStackId();
	}

	protected function encodePayload() : void
	{
		$this->putBlockPosition($this->x, $this->y, $this->z);
		$this->putUnsignedVarInt($this->blockId);
		$this->putVarInt($this->face);
		$this->putVector3($this->clickPos);
		$this->putVector3($this->playerPos);
		$this->putVarInt($this->slot);
		$this->putItemStackWithoutStackId($this->item);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleUseItem($this);
	}
}
