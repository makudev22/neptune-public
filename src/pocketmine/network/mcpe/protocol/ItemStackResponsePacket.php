<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\inventory\stackresponse\ItemStackResponse;

use function count;

class ItemStackResponsePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::ITEM_STACK_RESPONSE_PACKET;

	/** @var ItemStackResponse[] */
	public array $responses;

	/**
	 * @param ItemStackResponse[] $responses
	 */
	public static function create(array $responses) : self
	{
		$result = new self();
		$result->responses = $responses;
		return $result;
	}

	/** @return ItemStackResponse[] */
	public function getResponses() : array
	{
		return $this->responses;
	}

	protected function decodePayload() : void
	{
		$this->responses = [];
		for ($i = 0, $len = $this->getUnsignedVarInt(); $i < $len; ++$i) {
			$this->responses[] = ItemStackResponse::read($this);
		}
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt(count($this->responses));
		foreach ($this->responses as $response) {
			$response->write($this);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleItemStackResponse($this);
	}
}
