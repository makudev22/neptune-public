<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class LecternUpdatePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::LECTERN_UPDATE_PACKET;

	/** @var int */
	public $page;
	/** @var int */
	public $totalPages;
	/** @var int */
	public $x;
	/** @var int */
	public $y;
	/** @var int */
	public $z;
	/** @var bool */
	public $dropBook;

	protected function decodePayload() : void
	{
		$this->page = $this->getByte();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->totalPages = $this->getByte();
		}
		$this->getBlockPosition($this->x, $this->y, $this->z);
		if ($this->protocol < ProtocolInfo::PROTOCOL_662) {
			$this->dropBook = $this->getBool();
		}
	}

	protected function encodePayload() : void
	{
		$this->putByte($this->page);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->putByte($this->totalPages);
		}
		$this->putBlockPosition($this->x, $this->y, $this->z);
		if ($this->protocol < ProtocolInfo::PROTOCOL_662) {
			$this->putBool($this->dropBook);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleLecternUpdate($this);
	}
}
