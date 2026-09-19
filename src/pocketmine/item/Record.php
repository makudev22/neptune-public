<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\network\mcpe\protocol\ProtocolInfo;

class Record extends Item
{
	/** @var int */
	protected $soundId;

	public function __construct(int $id, int $soundId)
	{
		parent::__construct($id, 0, "Music Disc");

		$this->soundId = $soundId;
	}

	public function getMaxStackSize() : int
	{
		return 1;
	}

	public function getSoundId() : int
	{
		return $this->soundId;
	}

	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData
	{
		if ($playerProtocol < ProtocolInfo::PROTOCOL_407) {
			return new TranslatedItemData(ItemIds::SLIME_BALL, $this->getDamage(), $this->getName());
		}

		return parent::getItemProtocol($playerProtocol);
	}
}
