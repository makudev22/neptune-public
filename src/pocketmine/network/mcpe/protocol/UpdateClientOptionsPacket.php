<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\GraphicsMode;

class UpdateClientOptionsPacket extends DataPacket {
	public const NETWORK_ID = ProtocolInfo::UPDATE_CLIENT_OPTIONS_PACKET;

	private ?GraphicsMode $graphicsMode;
	private ?bool $filterProfanityChange = null;

	/**
	 * @generate-create-func
	 */
	public static function create(?GraphicsMode $graphicsMode, ?bool $filterProfanityChange) : self{
		$result = new self();
		$result->graphicsMode = $graphicsMode;
		$result->filterProfanityChange = $filterProfanityChange;
		return $result;
	}

	public function getGraphicsMode() : ?GraphicsMode{ return $this->graphicsMode; }

	public function getFilterProfanityChange() : ?bool{ return $this->filterProfanityChange; }

	protected function decodePayload() : void{
		$this->graphicsMode = $this->getOptional(fn () => GraphicsMode::fromPacket($this->getByte()));
		if ($this->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$this->filterProfanityChange = $this->getOptional($this->getBool(...));
		}
	}

	protected function encodePayload() : void{
		$this->putOptional($this->graphicsMode, fn (GraphicsMode $v) => $this->putByte($v->value));
		if ($this->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$this->putOptional($this->filterProfanityChange, $this->putBool(...));
		}
	}

	public function handle(NetworkSession $session) : bool{
		return $session->handleUpdateClientOptions($this);
	}
}
