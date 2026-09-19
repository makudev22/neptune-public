<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\ddui;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\ddui\update\BoolDataStoreUpdateValue;
use pocketmine\network\mcpe\protocol\types\ddui\update\DataStoreUpdateValue;
use pocketmine\network\mcpe\protocol\types\ddui\update\DataStoreUpdateValueType;
use pocketmine\network\mcpe\protocol\types\ddui\update\DoubleDataStoreUpdateValue;
use pocketmine\network\mcpe\protocol\types\ddui\update\StringDataStoreUpdateValue;

/**
 * @see ServerboundDataStorePacket&ClientboundDataStorePacket
 */
final class DataStoreUpdate extends DataStoreOperation{
	public const ID = DataStoreOperationType::UPDATE;

	public function __construct(
		private string $name,
		private string $property,
		private string $path,
		private DataStoreUpdateValue $data,
		private int $updateCount,
		private int $pathUpdateCount
	){}

	public function getTypeId() : DataStoreOperationType{
		return self::ID;
	}

	public function getName() : string{ return $this->name; }

	public function getProperty() : string{ return $this->property; }

	public function getPath() : string{ return $this->path; }

	public function getData() : DataStoreUpdateValue{ return $this->data; }

	public function getUpdateCount() : int{ return $this->updateCount; }

	public function getPathUpdateCount() : int{ return $this->pathUpdateCount; }

	public static function read(NetworkBinaryStream $in) : self{
		$name = $in->getString();
		$property = $in->getString();
		$path = $in->getString();

		$data = match($in->getUnsignedVarInt()){
			DataStoreUpdateValueType::DOUBLE => DoubleDataStoreUpdateValue::read($in),
			DataStoreUpdateValueType::BOOL => BoolDataStoreUpdateValue::read($in),
			DataStoreUpdateValueType::STRING => StringDataStoreUpdateValue::read($in),
			default => throw new PacketDecodeException("Unknown DataStoreValueType"),
		};

		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_924) {
			$updateCount = $in->getLInt();
			$pathUpdateCount = $in->getLInt();
		} else {
			$updateCount = $in->getVarInt();
		}

		return new self(
			$name,
			$property,
			$path,
			$data,
			$updateCount,
			$pathUpdateCount ?? 0
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->name);
		$out->putString($this->property);
		$out->putString($this->path);
		$out->putVarInt($this->data->getTypeId());
		$this->data->write($out);

		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_924) {
			$out->putLInt($this->updateCount);
			$out->putLInt($this->pathUpdateCount);
		} else {
			$out->putVarInt($this->updateCount);
		}
	}
}
