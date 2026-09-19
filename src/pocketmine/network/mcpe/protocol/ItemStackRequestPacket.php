<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\inventory\stackrequest\ItemStackRequest;

use function count;

class ItemStackRequestPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::ITEM_STACK_REQUEST_PACKET;

	/** @var ItemStackRequest[] */
	public array $requests;

	/**
	 * @param ItemStackRequest[] $requests
	 */
	public static function create(array $requests) : self
	{
		$result = new self();
		$result->requests = $requests;
		return $result;
	}

	/** @return ItemStackRequest[] */
	public function getRequests() : array
	{
		return $this->requests;
	}

	protected function decodePayload() : void
	{
		$this->requests = [];
		for ($i = 0, $len = $this->getUnsignedVarInt(); $i < $len; ++$i) {
			$this->requests[] = ItemStackRequest::read($this);
		}
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt(count($this->requests));
		foreach ($this->requests as $request) {
			$request->write($this);
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleItemStackRequest($this);
	}
}
