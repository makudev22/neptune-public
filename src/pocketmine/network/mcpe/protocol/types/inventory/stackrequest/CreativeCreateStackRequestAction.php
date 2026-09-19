<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * Creates an item by copying it from the creative inventory. This is treated as a crafting action by vanilla.
 */
final class CreativeCreateStackRequestAction extends ItemStackRequestAction
{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::CREATIVE_CREATE;

	public function __construct(
		private int $creativeItemId,
		private int $repetitions
	) {
	}

	public function getCreativeItemId() : int
	{
		return $this->creativeItemId;
	}

	public function getRepetitions() : int
	{
		return $this->repetitions;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$creativeItemId = $in->readCreativeItemNetId();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_712) {
			$repetitions = $in->getByte();
		}
		return new self($creativeItemId, $repetitions ?? 1);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->writeCreativeItemNetId($this->creativeItemId);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_712) {
			$out->putByte($this->repetitions);
		}
	}
}
