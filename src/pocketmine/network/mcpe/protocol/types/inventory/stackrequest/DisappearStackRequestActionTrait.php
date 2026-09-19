<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pocketmine\network\mcpe\NetworkBinaryStream;

trait DisappearStackRequestActionTrait
{
	final public function __construct(
		private int $count,
		private ItemStackRequestSlotInfo $source
	) {
	}

	final public function getCount() : int
	{
		return $this->count;
	}

	final public function getSource() : ItemStackRequestSlotInfo
	{
		return $this->source;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$count = $in->getByte();
		$source = ItemStackRequestSlotInfo::read($in);
		return new self($count, $source);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putByte($this->count);
		$this->source->write($out);
	}
}
