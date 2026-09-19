<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\convert\ConstantTranslator;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\InputMode;
use pocketmine\network\mcpe\protocol\types\InteractionMode;
use pocketmine\network\mcpe\protocol\types\inventory\stackrequest\ItemStackRequest;
use pocketmine\network\mcpe\protocol\types\ItemInteractionData;
use pocketmine\network\mcpe\protocol\types\PlayerAuthInputFlags;
use pocketmine\network\mcpe\protocol\types\PlayerAuthInputVehicleInfo;
use pocketmine\network\mcpe\protocol\types\PlayerBlockAction;
use pocketmine\network\mcpe\protocol\types\PlayerBlockActionStopBreak;
use pocketmine\network\mcpe\protocol\types\PlayerBlockActionWithBlockInfo;
use pocketmine\network\mcpe\protocol\types\PlayMode;
use function assert;
use function count;

class PlayerAuthInputPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::PLAYER_AUTH_INPUT_PACKET;

	private Vector3 $position;
	private float $pitch;
	private float $yaw;
	private float $headYaw;
	private float $moveVecX;
	private float $moveVecZ;
	private int $inputFlags;
	private array $additionalInputFlags = [];
	private int $inputMode;
	private int $playMode;
	private int $interactionMode;
	private ?Vector3 $vrGazeDirection = null;
	private Vector2 $interactRotation;
	private int $tick;
	private Vector3 $delta;
	private ?ItemInteractionData $itemInteractionData = null;
	private ?ItemStackRequest $itemStackRequest = null;
	/** @var PlayerBlockAction[]|null */
	private ?array $blockActions = null;
	private ?PlayerAuthInputVehicleInfo $vehicleInfo = null;
	private float $analogMoveVecX;
	private float $analogMoveVecZ;
	private Vector3 $cameraOrientation;
	private Vector2 $rawMove;

	/**
	 * @param int                      $inputFlags      @see PlayerAuthInputFlags
	 * @param int                      $inputMode       @see InputMode
	 * @param int                      $playMode        @see PlayMode
	 * @param int                      $interactionMode @see InteractionMode
	 * @param Vector3|null             $vrGazeDirection only used when PlayMode::VR
	 * @param PlayerBlockAction[]|null $blockActions    Blocks that the client has interacted with
	 */
	public static function create(
		Vector3 $position,
		float $pitch,
		float $yaw,
		float $headYaw,
		float $moveVecX,
		float $moveVecZ,
		int $inputFlags,
		int $inputMode,
		int $playMode,
		int $interactionMode,
		?Vector3 $vrGazeDirection,
		Vector2 $interactRotation,
		int $tick,
		Vector3 $delta,
		?ItemInteractionData $itemInteractionData,
		?ItemStackRequest $itemStackRequest,
		?array $blockActions,
		?PlayerAuthInputVehicleInfo $vehicleInfo,
		float $analogMoveVecX,
		float $analogMoveVecZ,
		Vector3 $cameraOrientation,
		Vector2 $rawMove,
	) : self {
		if ($playMode === PlayMode::VR && $vrGazeDirection === null) {
			//yuck, can we get a properly written packet just once? ...
			throw new \InvalidArgumentException("Gaze direction must be provided for VR play mode");
		}
		$result = new self();
		$result->position = $position->asVector3();
		$result->pitch = $pitch;
		$result->yaw = $yaw;
		$result->headYaw = $headYaw;
		$result->moveVecX = $moveVecX;
		$result->moveVecZ = $moveVecZ;

		$result->inputFlags = $inputFlags & ~((1 << PlayerAuthInputFlags::PERFORM_ITEM_STACK_REQUEST) | (1 << PlayerAuthInputFlags::PERFORM_ITEM_INTERACTION) | (1 << PlayerAuthInputFlags::PERFORM_BLOCK_ACTIONS));
		if ($itemStackRequest !== null) {
			$result->inputFlags |= 1 << PlayerAuthInputFlags::PERFORM_ITEM_STACK_REQUEST;
		}
		if ($itemInteractionData !== null) {
			$result->inputFlags |= 1 << PlayerAuthInputFlags::PERFORM_ITEM_INTERACTION;
		}
		if ($blockActions !== null) {
			$result->inputFlags |= 1 << PlayerAuthInputFlags::PERFORM_BLOCK_ACTIONS;
		}
		if ($vehicleInfo !== null) {
			$result->inputFlags |= 1 << PlayerAuthInputFlags::IN_CLIENT_PREDICTED_VEHICLE;
		}

		$result->inputMode = $inputMode;
		$result->playMode = $playMode;
		$result->interactionMode = $interactionMode;
		if ($vrGazeDirection !== null) {
			$result->vrGazeDirection = $vrGazeDirection->asVector3();
		}
		$result->interactRotation = $interactRotation;
		$result->tick = $tick;
		$result->delta = $delta;
		$result->itemInteractionData = $itemInteractionData;
		$result->itemStackRequest = $itemStackRequest;
		$result->blockActions = $blockActions;
		$result->vehicleInfo = $vehicleInfo;
		$result->analogMoveVecX = $analogMoveVecX;
		$result->analogMoveVecZ = $analogMoveVecZ;
		$result->cameraOrientation = $cameraOrientation;
		$result->rawMove = $rawMove;
		return $result;
	}

	public function getPosition() : Vector3
	{
		return $this->position;
	}

	public function getPitch() : float
	{
		return $this->pitch;
	}

	public function getYaw() : float
	{
		return $this->yaw;
	}

	public function getHeadYaw() : float
	{
		return $this->headYaw;
	}

	public function getMoveVecX() : float
	{
		return $this->moveVecX;
	}

	public function getMoveVecZ() : float
	{
		return $this->moveVecZ;
	}

	/**
	 * @see PlayerAuthInputFlags
	 */
	public function getInputFlags() : int
	{
		return $this->inputFlags;
	}

	/**
	 * @see InputMode
	 */
	public function getInputMode() : int
	{
		return $this->inputMode;
	}

	/**
	 * @see PlayMode
	 */
	public function getPlayMode() : int
	{
		return $this->playMode;
	}

	/**
	 * @see InteractionMode
	 */
	public function getInteractionMode() : int
	{
		return $this->interactionMode;
	}

	public function getVrGazeDirection() : ?Vector3
	{
		return $this->vrGazeDirection;
	}

	public function getInteractRotation() : Vector2
	{
		return $this->interactRotation;
	}

	public function getTick() : int
	{
		return $this->tick;
	}

	public function getDelta() : Vector3
	{
		return $this->delta;
	}

	public function getItemInteractionData() : ?ItemInteractionData
	{
		return $this->itemInteractionData;
	}

	public function getItemStackRequest() : ?ItemStackRequest
	{
		return $this->itemStackRequest;
	}

	/**
	 * @return PlayerBlockAction[]|null
	 */
	public function getBlockActions() : ?array
	{
		return $this->blockActions;
	}

	public function getVehicleInfo() : ?PlayerAuthInputVehicleInfo
	{
		return $this->vehicleInfo;
	}

	public function getAnalogMoveVecX() : float
	{
		return $this->analogMoveVecX;
	}

	public function getAnalogMoveVecZ() : float
	{
		return $this->analogMoveVecZ;
	}

	public function getCameraOrientation() : Vector3
	{
		return $this->cameraOrientation;
	}

	public function getRawMove() : Vector2
	{
		return $this->rawMove;
	}

	public function hasFlag(int $flag) : bool
	{
		if ($flag >= PHP_INT_SIZE * 8) {
			return isset($this->additionalInputFlags[$flag]);
		}
		return ($this->inputFlags & (1 << $flag)) !== 0;
	}

	protected function decodePayload() : void
	{
		$this->pitch = $this->getLFloat();
		$this->yaw = $this->getLFloat();
		$this->position = $this->getVector3();
		$this->moveVecX = $this->getLFloat();
		$this->moveVecZ = $this->getLFloat();
		$this->headYaw = $this->getLFloat();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->inputFlags = 0;
			$this->additionalInputFlags = [];
			for ($i = 0, $count = $this->getUnsignedVarInt(); $i < $count; ++$i) {
				$flag = $this->getVarInt();
				if ($flag < 0 || $flag >= PlayerAuthInputFlags::NUMBER_OF_FLAGS) {
					throw new PacketDecodeException("Invalid input flag $flag");
				}
				if ($flag < PHP_INT_SIZE * 8) {
					$this->inputFlags |= 1 << $flag;
				} else {
					$this->additionalInputFlags[$flag] = true;
				}
			}
		} else {
			$this->inputFlags = $this->getUnsignedVarLong();
		}
		$this->inputMode = $this->getUnsignedVarInt();
		$this->playMode = $this->getUnsignedVarInt();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_527) {
			$this->interactionMode = $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->getVarInt() : $this->getUnsignedVarInt();
		}
		if ($this->protocol >= ProtocolInfo::PROTOCOL_748) {
			$this->interactRotation = $this->getVector2();
		} else {
			if ($this->playMode === PlayMode::VR) {
				$this->vrGazeDirection = $this->getVector3();
			}
		}
		if ($this->protocol >= ProtocolInfo::PROTOCOL_419) {
			$this->tick = $this->getUnsignedVarLong();
			$this->delta = $this->getVector3();

				if ($this->protocol >= ProtocolInfo::PROTOCOL_428) {
					$hasItemInteraction = $this->hasFlag(PlayerAuthInputFlags::PERFORM_ITEM_INTERACTION);
					if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
						$hasItemInteraction = $this->getBool();
					}
					if ($hasItemInteraction) {
						$this->itemInteractionData = ItemInteractionData::read($this);
					}
					$hasItemStackRequest = $this->hasFlag(PlayerAuthInputFlags::PERFORM_ITEM_STACK_REQUEST);
					if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
						$hasItemStackRequest = $this->getBool();
					}
					if ($hasItemStackRequest) {
						$this->itemStackRequest = ItemStackRequest::read($this);
					}
					$hasBlockActions = $this->hasFlag(PlayerAuthInputFlags::PERFORM_BLOCK_ACTIONS);
					if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
						$hasBlockActions = $this->getBool();
					}
					if ($hasBlockActions) {
					$this->blockActions = [];
					$max = $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->getUnsignedVarInt() : $this->getVarInt();
					for ($i = 0; $i < $max; ++$i) {
						$actionType = $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->getVarInt() : ConstantTranslator::getInstance()->fromNetworkId(PlayerActionPacket::class, $this->getVarInt(), $this->protocol);
						$this->blockActions[] = match (true) {
							PlayerBlockActionWithBlockInfo::isValidActionType($actionType) => PlayerBlockActionWithBlockInfo::read($this, $actionType),
							$actionType === PlayerActionPacket::ACTION_STOP_BREAK => new PlayerBlockActionStopBreak(),
							default => throw new PacketDecodeException("Unexpected block action type $actionType")
						};
					}
				}

				if ($this->protocol >= ProtocolInfo::PROTOCOL_649) {
					if ($this->protocol >= ProtocolInfo::PROTOCOL_2193 || $this->hasFlag(PlayerAuthInputFlags::IN_CLIENT_PREDICTED_VEHICLE)) {
						$vehicleInfo = PlayerAuthInputVehicleInfo::read($this);
						$this->vehicleInfo = $vehicleInfo->isNull() ? null : $vehicleInfo;
					}
				}

				if ($this->protocol >= ProtocolInfo::PROTOCOL_575) {
					$this->analogMoveVecX = $this->getLFloat();
					$this->analogMoveVecZ = $this->getLFloat();
					if ($this->protocol >= ProtocolInfo::PROTOCOL_748) {
						$this->cameraOrientation = $this->getVector3();
						if ($this->protocol >= ProtocolInfo::PROTOCOL_766) {
							$this->rawMove = $this->getVector2();
						}
					}
				}
			}
		}
	}

	protected function encodePayload() : void
	{
		$this->putLFloat($this->pitch);
		$this->putLFloat($this->yaw);
		$this->putVector3($this->position);
		$this->putLFloat($this->moveVecX);
		$this->putLFloat($this->moveVecZ);
		$this->putLFloat($this->headYaw);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$flags = [];
			for ($flag = 0; $flag < PHP_INT_SIZE * 8; ++$flag) {
				if (($this->inputFlags & (1 << $flag)) !== 0) {
					$flags[] = $flag;
				}
			}
			foreach (array_keys($this->additionalInputFlags) as $flag) {
				$flags[] = $flag;
			}
			sort($flags, SORT_NUMERIC);
			$this->putUnsignedVarInt(count($flags));
			foreach ($flags as $flag) {
				$this->putVarInt($flag);
			}
		} else {
			$this->putUnsignedVarLong($this->inputFlags);
		}
		$this->putUnsignedVarInt($this->inputMode);
		$this->putUnsignedVarInt($this->playMode);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_527) {
			if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
				$this->putVarInt($this->interactionMode);
			} else {
				$this->putUnsignedVarInt($this->interactionMode);
			}
		}

		if ($this->protocol >= ProtocolInfo::PROTOCOL_748) {
			$this->putVector2($this->interactRotation);
		} else {
			if ($this->playMode === PlayMode::VR) {
				assert($this->vrGazeDirection !== null);
				$this->putVector3($this->vrGazeDirection);
			}
		}
		if ($this->protocol >= ProtocolInfo::PROTOCOL_419) {
			$this->putUnsignedVarLong($this->tick);
			$this->putVector3($this->delta);

			if ($this->protocol >= ProtocolInfo::PROTOCOL_471) {
				if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
					$this->putBool($this->itemInteractionData !== null);
					$this->itemInteractionData?->write($this);
					$this->putBool($this->itemStackRequest !== null);
					$this->itemStackRequest?->write($this);
					$this->putBool($this->blockActions !== null);
					if ($this->blockActions !== null) {
						$this->putUnsignedVarInt(count($this->blockActions));
						foreach ($this->blockActions as $blockAction) {
							$this->putVarInt($blockAction->getActionType());
							$blockAction->write($this);
						}
					}
					($this->vehicleInfo ?? new PlayerAuthInputVehicleInfo())->write($this);
					$this->putLFloat($this->analogMoveVecX);
					$this->putLFloat($this->analogMoveVecZ);
					$this->putVector3($this->cameraOrientation);
					$this->putVector2($this->rawMove);
					return;
				}
				if ($this->itemInteractionData !== null) {
					$this->itemInteractionData->write($this);
				}
				if ($this->itemStackRequest !== null) {
					$this->itemStackRequest->write($this);
				}
				if ($this->blockActions !== null) {
					$this->putVarInt(count($this->blockActions));
					foreach ($this->blockActions as $blockAction) {
						$this->putVarInt(ConstantTranslator::getInstance()->toNetworkId(PlayerActionPacket::class, $blockAction->getActionType(), $this->protocol));
						$blockAction->write($this);
					}
				}

				if ($this->protocol >= ProtocolInfo::PROTOCOL_649) {
					if ($this->vehicleInfo !== null) {
						$this->vehicleInfo->write($this);
					}
				}

				if ($this->protocol >= ProtocolInfo::PROTOCOL_575) {
					$this->putLFloat($this->analogMoveVecX);
					$this->putLFloat($this->analogMoveVecZ);
					if ($this->protocol >= ProtocolInfo::PROTOCOL_748) {
						$this->putVector3($this->cameraOrientation);
						if ($this->protocol >= ProtocolInfo::PROTOCOL_766) {
							$this->putVector2($this->rawMove);
						}
					}
				}
			}
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handlePlayerAuthInput($this);
	}
}
