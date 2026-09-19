<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\camera\CameraAimAssistActionType;

class CameraAimAssistInstructionPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CAMERA_AIM_ASSIST_INSTRUCTION_PACKET;

	private string $presetId;
	private CameraAimAssistActionType $actionType;
	private bool $allowAimAssist;

	/**
	 * @generate-create-func
	 */
	public static function create(string $presetId, CameraAimAssistActionType $actionType, bool $allowAimAssist) : self
	{
		$result = new self();
		$result->presetId = $presetId;
		$result->actionType = $actionType;
		$result->allowAimAssist = $allowAimAssist;
		return $result;
	}

	public function getPresetId() : string
	{
		return $this->presetId;
	}

	public function getActionType() : CameraAimAssistActionType
	{
		return $this->actionType;
	}

	public function getAllowAimAssist() : bool
	{
		return $this->allowAimAssist;
	}

	protected function decodePayload() : void
	{
		$this->presetId = $this->getString();
		$this->actionType = CameraAimAssistActionType::fromPacket($this->getByte());
		$this->allowAimAssist = $this->getBool();
	}

	protected function encodePayload() : void
	{
		$this->putString($this->presetId);
		$this->putByte($this->actionType->value);
		$this->putBool($this->allowAimAssist);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleCameraAimAssistInstruction($this);
	}
}
