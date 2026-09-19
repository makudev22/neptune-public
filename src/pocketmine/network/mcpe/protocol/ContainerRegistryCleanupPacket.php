<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\inventory\FullContainerName;

use function count;

class ContainerRegistryCleanupPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CONTAINER_REGISTRY_CLEANUP_PACKET;
	/** @var FullContainerName[] */
	private array $removedContainers;
	/**
	 * @generate-create-func
	 * @param FullContainerName[] $removedContainers
	 */
	public static function create(array $removedContainers) : self
	{
		$result = new self();
		$result->removedContainers = $removedContainers;
		return $result;
	}
	/**
	 * @return FullContainerName[]
	 */
	public function getRemovedContainers() : array
	{
		return $this->removedContainers;
	}

	protected function decodePayload() : void
	{
		$this->removedContainers = [];
		for ($i = 0, $len = $this->getUnsignedVarInt(); $i < $len; ++$i) {
			$this->removedContainers[] = FullContainerName::read($this);
		}
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt(count($this->removedContainers));
		foreach ($this->removedContainers as $container) {
			$container->write($this);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleContainerRegistryCleanup($this);
	}
}
