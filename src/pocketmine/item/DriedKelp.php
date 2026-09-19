<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\network\mcpe\protocol\ProtocolInfo;

class DriedKelp extends Food
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::DRIED_KELP, $meta, "Dried Kelp");
	}

	public function getFoodRestore() : int
	{
		return 1;
	}

	public function getSaturationRestore() : float
	{
		return 0.6;
	}

	public function getItemProtocol(int $playerProtocol) : ?TranslatedItemData
	{
		if ($playerProtocol < ProtocolInfo::PROTOCOL_407) {
			return new TranslatedItemData(ItemIds::APPLE, $this->getDamage(), $this->getName());
		}

		return parent::getItemProtocol($playerProtocol);
	}
}
