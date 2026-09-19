<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\utils\BinaryDataException;
use UnexpectedValueException as PacketDecodeException;

use function count;

abstract class TransactionData
{
	/** @var NetworkInventoryAction[] */
	protected array $actions = [];

	protected bool $hasItemStackIds = true;

	/**
	 * @return NetworkInventoryAction[]
	 */
	final public function getActions() : array
	{
		return $this->actions;
	}

	abstract public function getTypeId() : int;

	/**
	 * @throws BinaryDataException
	 * @throws PacketDecodeException
	 */
	final public function decode(NetworkBinaryStream $in, bool $legacyTransaction) : void
	{
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_407 && $in->getProtocol() < ProtocolInfo::PROTOCOL_431) {
			$this->hasItemStackIds = $in->getBool();
		}

		$actionCount = $in->getUnsignedVarInt();
		for ($i = 0; $i < $actionCount; ++$i) {
			$this->actions[] = (new NetworkInventoryAction())->read($in, $legacyTransaction, $this->hasItemStackIds);
		}
		$this->decodeData($in, $legacyTransaction);
	}

	/**
	 * @throws BinaryDataException
	 * @throws PacketDecodeException
	 */
	abstract protected function decodeData(NetworkBinaryStream $in, bool $legacyTransaction) : void;

	final public function encode(NetworkBinaryStream $out, bool $legacyTransaction) : void
	{
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_407 && $out->getProtocol() < ProtocolInfo::PROTOCOL_431) {
			$out->putBool($this->hasItemStackIds);
		}

		$out->putUnsignedVarInt(count($this->actions));
		foreach ($this->actions as $action) {
			$action->write($out, $legacyTransaction, $this->hasItemStackIds);
		}
		$this->encodeData($out, $legacyTransaction);
	}

	abstract protected function encodeData(NetworkBinaryStream $out, bool $legacyTransaction) : void;
}
