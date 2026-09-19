<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

class Tripwire extends Flowable
{
	protected $id = self::TRIPWIRE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Tripwire";
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [
			ItemFactory::get(Item::STRING)
		];
	}

	public function isAffectedBySilkTouch() : bool
	{
		return false;
	}
}
