<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

class UseItemTransactionData extends TransactionData
{
	use GetTypeIdFromConstTrait;

	public const ID = InventoryTransactionPacket::TYPE_USE_ITEM;

	public const ACTION_CLICK_BLOCK = 0;
	public const ACTION_CLICK_AIR = 1;
	public const ACTION_BREAK_BLOCK = 2;
	public const ACTION_USE_AS_ATTACK = 3;

	private int $actionType;
	private TriggerType $triggerType;
	private Vector3 $blockPos;
	private int $face;
	private int $hotbarSlot;
	private int $hand = 0;
	private ItemStackWrapper $itemInHand;
	private Vector3 $playerPos;
	private Vector3 $clickPos;
	private int $blockRuntimeId;
	private PredictedResult $clientInteractPrediction = PredictedResult::SUCCESS;
	private int $clientCooldownState;

	public function getActionType() : int
	{
		return $this->actionType;
	}

	public function getTriggerType() : TriggerType
	{
		return $this->triggerType;
	}

	public function getBlockPosition() : Vector3
	{
		return $this->blockPos;
	}

	public function getFace() : int
	{
		return $this->face;
	}

	public function getHotbarSlot() : int
	{
		return $this->hotbarSlot;
	}

	public function getItemInHand() : ItemStackWrapper
	{
		return $this->itemInHand;
	}

	public function getPlayerPosition() : Vector3
	{
		return $this->playerPos;
	}

	public function getClickPosition() : Vector3
	{
		return $this->clickPos;
	}

	public function getBlockRuntimeId() : int
	{
		return $this->blockRuntimeId;
	}

	public function getClientInteractPrediction() : PredictedResult
	{
		return $this->clientInteractPrediction;
	}

	public function getClientCooldownState() : int{
		return $this->clientCooldownState;
	}

	protected function decodeData(NetworkBinaryStream $in, bool $legacyTransaction) : void
	{
		$this->actionType = $in->getProtocol() >= ProtocolInfo::PROTOCOL_1001 && $legacyTransaction ? $in->getVarInt() : $in->getUnsignedVarInt();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_712) {
			$this->triggerType = TriggerType::fromPacket($in->getProtocol() >= ProtocolInfo::PROTOCOL_1001 && $legacyTransaction ? $in->getByte() : $in->getUnsignedVarInt());
		}
		$x = $y = $z = 0;
		$in->getBlockPosition($x, $y, $z);
		$this->blockPos = new Vector3($x, $y, $z);
		$this->face = $in->getProtocol() >= ProtocolInfo::PROTOCOL_1001 && $legacyTransaction ? $in->getByte() : $in->getVarInt();
		$this->hotbarSlot = $in->getVarInt();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_2193) {
			$this->hand = $in->getByte();
		}
		$this->itemInHand = $in->getProtocol() >= ProtocolInfo::PROTOCOL_1001 && $legacyTransaction ? $in->getNetworkItemStackDescriptor() : $in->getItemStackWrapper();
		$this->playerPos = $in->getVector3();
		$this->clickPos = $in->getVector3();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_407) {
			$this->blockRuntimeId = $in->getUnsignedVarInt();
			if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_712) {
				$this->clientInteractPrediction = PredictedResult::fromPacket($in->getProtocol() >= ProtocolInfo::PROTOCOL_1001 && $legacyTransaction ? $in->getByte() : $in->getUnsignedVarInt());
				if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_944) {
					$this->clientCooldownState = $in->getByte();
				}
			}
		}
	}

	protected function encodeData(NetworkBinaryStream $out, bool $legacyTransaction) : void
	{
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_1001 && $legacyTransaction) {
			$out->putVarInt($this->actionType);
		} else {
			$out->putUnsignedVarInt($this->actionType);
		}
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_712) {
			if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_1001 && $legacyTransaction) {
				$out->putByte($this->triggerType->value);
			} else {
				$out->putUnsignedVarInt($this->triggerType->value);
			}
		}
		$out->putBlockPosition($this->blockPos->x, $this->blockPos->y, $this->blockPos->z);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_1001 && $legacyTransaction) {
			$out->putByte($this->face);
		} else {
			$out->putVarInt($this->face);
		}
		$out->putVarInt($this->hotbarSlot);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_2193) {
			$out->putByte($this->hand);
		}
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_1001 && $legacyTransaction) {
			$out->putNetworkItemStackDescriptor($this->itemInHand);
		} else {
			$out->putItemStackWrapper($this->itemInHand);
		}
		$out->putVector3($this->playerPos);
		$out->putVector3($this->clickPos);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_407) {
			$out->putUnsignedVarInt($this->blockRuntimeId);
			if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_712) {
				if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_1001 && $legacyTransaction) {
					$out->putByte($this->clientInteractPrediction->value);
				} else {
					$out->putUnsignedVarInt($this->clientInteractPrediction->value);
				}
				if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_944) {
					$out->putByte($this->clientCooldownState);
				}
			}
		}
	}

	/**
	 * @param NetworkInventoryAction[] $actions
	 */
	public static function new(array $actions, int $actionType, TriggerType $triggerType, Vector3 $blockPos, int $face, int $hotbarSlot, ItemStackWrapper $itemInHand, Vector3 $playerPos, Vector3 $clickPos, int $blockRuntimeId, PredictedResult $clientPrediction, int $clientCooldownState) : self
	{
		$result = new self();
		$result->actions = $actions;
		$result->actionType = $actionType;
		$result->triggerType = $triggerType;
		$result->blockPos = $blockPos;
		$result->face = $face;
		$result->hotbarSlot = $hotbarSlot;
		$result->itemInHand = $itemInHand;
		$result->playerPos = $playerPos;
		$result->clickPos = $clickPos;
		$result->blockRuntimeId = $blockRuntimeId;
		$result->clientInteractPrediction = $clientPrediction;
		$result->clientCooldownState = $clientCooldownState;
		return $result;
	}
}
