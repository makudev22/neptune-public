<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\hud\ServerboundLoadingScreenPacketType;

class ServerboundLoadingScreenPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SERVERBOUND_LOADING_SCREEN_PACKET;

	private ServerboundLoadingScreenPacketType $loadingScreenType;
	private ?int $loadingScreenId = null;

	/**
	 * @generate-create-func
	 */
	public static function create(ServerboundLoadingScreenPacketType $loadingScreenType, ?int $loadingScreenId) : self
	{
		$result = new self();
		$result->loadingScreenType = $loadingScreenType;
		$result->loadingScreenId = $loadingScreenId;
		return $result;
	}

	public function getLoadingScreenType() : ServerboundLoadingScreenPacketType
	{
		return $this->loadingScreenType;
	}

	public function getLoadingScreenId() : ?int
	{
		return $this->loadingScreenId;
	}

	protected function decodePayload() : void
	{
		$this->loadingScreenType = ServerboundLoadingScreenPacketType::fromPacket($this->getVarInt());
		$this->loadingScreenId = $this->getOptional(fn () => $this->getLInt());
	}

	protected function encodePayload() : void
	{
		$this->putVarInt($this->loadingScreenType->value);
		$this->putOptional($this->loadingScreenId, $this->putLInt(...));
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleServerboundLoadingScreen($this);
	}
}
