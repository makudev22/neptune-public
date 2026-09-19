<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\ddui;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * @see ClientboundDataStorePacket
 */
final class DataStoreRemoval extends DataStoreOperation{
	use GetTypeIdFromConstTrait;

	public const ID = DataStoreOperationType::REMOVAL;

	public function __construct(
		private string $name,
	){}

	public function getName() : string{ return $this->name; }

	public static function read(NetworkBinaryStream $in) : self{
		$name = $in->getString();

		return new self(
			$name,
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->name);
	}
}
