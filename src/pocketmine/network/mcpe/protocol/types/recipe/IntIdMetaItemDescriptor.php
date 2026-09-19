<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class IntIdMetaItemDescriptor implements ItemDescriptor{
	use GetTypeIdFromConstTrait;

	public const ID = ItemDescriptorType::INT_ID_META;

	public function __construct(
		private int $id,
		private int $meta
	){
		if($id === 0 && $meta !== 0){
			throw new \InvalidArgumentException("Meta cannot be non-zero for air");
		}
	}

	public function getId() : int{ return $this->id; }

	public function getMeta() : int{ return $this->meta; }

	public static function read(NetworkBinaryStream $in) : self{
		$id = $in->getSignedLShort();
		if($id !== 0){
			$meta = $in->getSignedLShort();
		}else{
			$meta = 0;
		}

		return new self($id, $meta);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putLShort($this->id);
		if($this->id !== 0){
			$out->putLShort($this->meta);
		}
	}
}
