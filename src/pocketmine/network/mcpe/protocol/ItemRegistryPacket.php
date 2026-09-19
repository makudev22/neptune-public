<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;

use function count;

class ItemRegistryPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::ITEM_REGISTRY_PACKET;

	/**
	 * @var ItemTypeEntry[]
	 * @phpstan-var list<ItemTypeEntry>
	 */
	private array $entries;

	/**
	 * @generate-create-func
	 * @param ItemTypeEntry[] $entries
	 * @phpstan-param list<ItemTypeEntry> $entries
	 */
	public static function create(array $entries) : self
	{
		$result = new self();
		$result->entries = $entries;
		return $result;
	}

	/**
	 * @return ItemTypeEntry[]
	 * @phpstan-return list<ItemTypeEntry>
	 */
	public function getEntries() : array
	{
		return $this->entries;
	}

	protected function decodePayload() : void
	{
		$this->entries = [];
		for ($i = 0, $len = $this->getUnsignedVarInt(); $i < $len; ++$i) {
			$stringId = $this->getString();
			$numericId = $this->getSignedLShort();
			$isComponentBased = $this->getBool();
			$version = $this->getVarInt();
			$nbt = $this->getNbtCompoundRoot();
			$this->entries[] = new ItemTypeEntry($stringId, $numericId, $isComponentBased, $version, $nbt);
		}
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt(count($this->entries));
		foreach ($this->entries as $entry) {
			$this->putString($entry->getStringId());
			$this->putLShort($entry->getNumericId());
			$this->putBool($entry->isComponentBased());
			$this->putVarInt($entry->getVersion());
			$this->put((new NetworkLittleEndianNBTStream())->write($entry->getComponentNbt()));
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleItemRegistry($this);
	}
}
