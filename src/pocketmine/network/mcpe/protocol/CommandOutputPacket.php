<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\command\CommandOriginData;
use pocketmine\network\mcpe\protocol\types\command\CommandOutputMessage;

use pocketmine\network\mcpe\protocol\types\OutputType;
use function count;

class CommandOutputPacket extends DataPacket {
	public const NETWORK_ID = ProtocolInfo::COMMAND_OUTPUT_PACKET;

	public CommandOriginData $originData;
	public OutputType $outputType;
	public int $successCount;
	/** @var CommandOutputMessage[] */
	public array $messages = [];
	public ?string $data = null;

	protected function decodePayload() : void
	{
		$this->originData = $this->getCommandOriginData();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_897) {
			$this->outputType = OutputType::fromName($this->getString());
		} else {
			$this->outputType = OutputType::fromPacket($this->getByte());
		}

		if ($this->protocol >= ProtocolInfo::PROTOCOL_897) {
			$this->successCount = $this->getInt();
		} else {
			$this->successCount = $this->getUnsignedVarInt();
		}

		for ($i = 0, $size = $this->getUnsignedVarInt(); $i < $size; ++$i) {
			$this->messages[] = $this->getCommandMessage();
		}

		if ($this->protocol >= ProtocolInfo::PROTOCOL_897) {
			$this->data = $this->getOptional($this->getString(...));
		} else {
			if ($this->outputType === OutputType::DATA_SET) {
				$this->data = $this->getString();
			}
		}
	}

	protected function getCommandMessage() : CommandOutputMessage{
		$message = new CommandOutputMessage();

		if ($this->protocol >= ProtocolInfo::PROTOCOL_897) {
			$message->messageId = $this->getString();
			$message->isInternal = $this->getBool();
		} else {
			$message->isInternal = $this->getBool();
			$message->messageId = $this->getString();
		}

		for ($i = 0, $size = $this->getUnsignedVarInt(); $i < $size; ++$i) {
			$message->parameters[] = $this->getString();
		}

		return $message;
	}

	protected function encodePayload() : void{
		$this->putCommandOriginData($this->originData);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_897) {
			$this->putString($this->outputType->getName());
		} else {
			$this->putByte($this->outputType->value);
		}

		if ($this->protocol >= ProtocolInfo::PROTOCOL_897) {
			$this->putInt($this->successCount);
		} else {
			$this->putUnsignedVarInt($this->successCount);
		}

		$this->putUnsignedVarInt(count($this->messages));
		foreach ($this->messages as $message) {
			$this->putCommandMessage($message);
		}

		if ($this->protocol >= ProtocolInfo::PROTOCOL_897) {
			$this->putOptional($this->data, $this->putString(...));
		} else {
			if ($this->outputType === OutputType::DATA_SET) {
				$this->putString($this->data);
			}
		}
	}

	protected function putCommandMessage(CommandOutputMessage $message) : void{
		$this->putBool($message->isInternal);
		$this->putString($message->messageId);

		$this->putUnsignedVarInt(count($message->parameters));
		foreach ($message->parameters as $parameter) {
			$this->putString($parameter);
		}
	}

	public function mustBeDecoded() : bool{
		return false;
	}

	public function handle(NetworkSession $session) : bool{
		return $session->handleCommandOutput($this);
	}
}
