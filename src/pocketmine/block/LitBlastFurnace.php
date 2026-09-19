<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\ItemIds;

class LitBlastFurnace extends BlastFurnace
{
	protected $id = self::LIT_BLAST_FURNACE;
	protected $itemId = ItemIds::BLAST_FURNACE;

	public function getName() : string
	{
		return "Lit Blast Furnace";
	}

	public function getLightLevel() : int
	{
		return 13;
	}
}
