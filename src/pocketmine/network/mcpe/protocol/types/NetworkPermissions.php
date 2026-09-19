<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class NetworkPermissions
{
	public function __construct(
		private bool $disableClientSounds
	) {
	}

	public function disableClientSounds() : bool
	{
		return $this->disableClientSounds;
	}

	public static function decode(NetworkBinaryStream $in) : self
	{
		$disableClientSounds = $in->getBool();
		return new self($disableClientSounds);
	}

	public function encode(NetworkBinaryStream $out) : void
	{
		$out->putBool($this->disableClientSounds);
	}
}
