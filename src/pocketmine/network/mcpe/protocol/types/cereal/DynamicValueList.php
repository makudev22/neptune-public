<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\cereal;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;
use function count;

final class DynamicValueList extends DynamicValue{
	use GetTypeIdFromConstTrait;

	public const ID = DynamicValueType::LIST;

	/**
	 * @param (DynamicValue|null)[] $values
	 * @phpstan-param list<DynamicValue|null> $values
	 */
	public function __construct(
		private array $values
	){}

	/**
	 * @return (DynamicValue|null)[]
	 * @phpstan-return list<DynamicValue|null>
	 */
	public function getValues() : array{
		return $this->values;
	}

	protected static function readValue(NetworkBinaryStream $in) : self{
		$size = $in->getUnsignedVarInt();
		$values = [];
		for($i = 0; $i < $size; ++$i){
			$type = $in->getLInt();
			$values[] = DynamicValue::read($in, $type);
		}
		return new self($values);
	}

	protected function writeValue(NetworkBinaryStream $out) : void{
		$out->putUnsignedVarInt(count($this->values));
		foreach($this->values as $value){
			$out->putLInt($value?->getTypeId() ?? DynamicValueType::NULL);
			$value?->write($out);
		}
	}
}
