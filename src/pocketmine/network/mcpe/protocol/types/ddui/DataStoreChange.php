<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\ddui;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\cereal\DynamicValue;
use pocketmine\network\mcpe\protocol\types\cereal\DynamicValueType;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * @see ClientboundDataStorePacket
 */
final class DataStoreChange extends DataStoreOperation {
	use GetTypeIdFromConstTrait;

	public const ID = DataStoreOperationType::CHANGE;

	public function __construct(
		private string $name,
		private string $property,
		private int $updateCount,
		private ?DynamicValue $data
	){}

	public function getName() : string{ return $this->name; }

	public function getProperty() : string{ return $this->property; }

	public function getUpdateCount() : int{ return $this->updateCount; }

	public function getData() : ?DynamicValue{ return $this->data; }

	public static function read(NetworkBinaryStream $in) : self{
		$name = $in->getString();
		$property = $in->getString();
		$updateCount = $in->getUnsignedVarInt();

		$type = $in->getLInt();
		$data = DynamicValue::read($in, $type);

		return new self(
			$name,
			$property,
			$updateCount,
			$data,
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->name);
		$out->putString($this->property);
		$out->putUnsignedVarInt($this->updateCount);

		//TODO: yucky, we really need to revamp how unions are handled :(
		$type = $this->data?->getTypeId() ?? DynamicValueType::NULL;
		$out->putLInt($type);
		$this->data?->write($out);
	}
}
