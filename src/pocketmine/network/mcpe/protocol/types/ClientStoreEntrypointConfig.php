<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

/**
 * @see ServerStoreInfoPacket
 */
final class ClientStoreEntrypointConfig{
	public function __construct(
		private string $storeId,
		private string $storeName
	){}

	public function getStoreId() : string{ return $this->storeId; }

	public function getStoreName() : string{ return $this->storeName; }

	public static function read(NetworkBinaryStream $in) : self{
		$storeId = $in->getString();
		$storeName = $in->getString();

		return new self($storeId, $storeName);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->storeId);
		$out->putString($this->storeName);
	}
}
