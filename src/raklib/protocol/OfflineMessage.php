<?php


declare(strict_types=1);

namespace raklib\protocol;

use pocketmine\utils\BinaryDataException;
use pocketmine\utils\BinaryStream;

abstract class OfflineMessage extends Packet
{
	/**
	 * Magic bytes used to distinguish offline messages from loose garbage.
	 */
	private const MAGIC = "\x00\xff\xff\x00\xfe\xfe\xfe\xfe\xfd\xfd\xfd\xfd\x12\x34\x56\x78";

	protected string $magic = self::MAGIC;

	/**
	 * @return void
	 * @throws BinaryDataException
	 */
	protected function readMagic(BinaryStream $in)
	{
		$this->magic = $in->get(16);
	}

	/**
	 * @return void
	 */
	protected function writeMagic(BinaryStream $out)
	{
		$out->put($this->magic);
	}

	public function isValid() : bool
	{
		return $this->magic === self::MAGIC;
	}
}
