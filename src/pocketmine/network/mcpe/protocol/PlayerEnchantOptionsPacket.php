<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\EnchantOption;

use function count;

class PlayerEnchantOptionsPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::PLAYER_ENCHANT_OPTIONS_PACKET;

	/** @var EnchantOption[] */
	private array $options;

	/**
	 * @generate-create-func
	 * @param EnchantOption[] $options
	 */
	public static function create(array $options) : self
	{
		$result = new self();
		$result->options = $options;
		return $result;
	}

	/**
	 * @return EnchantOption[]
	 */
	public function getOptions() : array
	{
		return $this->options;
	}

	protected function decodePayload() : void
	{
		$this->options = [];
		for ($i = 0, $len = $this->getUnsignedVarInt(); $i < $len; ++$i) {
			$this->options[] = EnchantOption::read($this);
		}
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt(count($this->options));
		foreach ($this->options as $option) {
			$option->write($this);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handlePlayerEnchantOptions($this);
	}
}
