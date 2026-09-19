<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

use function count;

class PlayerFogPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::PLAYER_FOG_PACKET;

	/**
	 * @var string[]
	 * @phpstan-var list<string>
	 */
	public array $fogLayers;

	/**
	 * @param string[] $fogLayers
	 * @phpstan-param list<string> $fogLayers
	 */
	public static function create(array $fogLayers) : self
	{
		$result = new self();
		$result->fogLayers = $fogLayers;
		return $result;
	}

	/**
	 * @return string[]
	 * @phpstan-return list<string>
	 */
	public function getFogLayers() : array
	{
		return $this->fogLayers;
	}

	protected function decodePayload() : void
	{
		$this->fogLayers = [];
		for ($i = 0, $len = $this->getUnsignedVarInt(); $i < $len; ++$i) {
			$this->fogLayers[] = $this->getString();
		}
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt(count($this->fogLayers));
		foreach ($this->fogLayers as $fogLayer) {
			$this->putString($fogLayer);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handlePlayerFog($this);
	}
}
