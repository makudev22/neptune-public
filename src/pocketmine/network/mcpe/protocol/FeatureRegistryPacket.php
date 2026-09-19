<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\FeatureRegistryPacketEntry;

use function count;

/**
 * Syncs world generator settings from server to client, for client-sided chunk generation.
 */
class FeatureRegistryPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::FEATURE_REGISTRY_PACKET;

	/** @var FeatureRegistryPacketEntry[] */
	private array $entries;

	/**
	 * @generate-create-func
	 * @param FeatureRegistryPacketEntry[] $entries
	 */
	public static function create(array $entries) : self
	{
		$result = new self();
		$result->entries = $entries;
		return $result;
	}

	/** @return FeatureRegistryPacketEntry[] */
	public function getEntries() : array
	{
		return $this->entries;
	}

	protected function decodePayload() : void
	{
		for ($this->entries = [], $i = 0, $count = $this->getUnsignedVarInt(); $i < $count; $i++) {
			$this->entries[] = FeatureRegistryPacketEntry::read($this);
		}
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt(count($this->entries));
		foreach ($this->entries as $entry) {
			$entry->write($this);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleFeatureRegistry($this);
	}
}
