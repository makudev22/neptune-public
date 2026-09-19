<?php


declare(strict_types=1);

namespace pocketmine\block;

class LitDeepslateRedstoneOre extends GlowingRedstoneOre
{
	protected $id = self::LIT_DEEPSLATE_REDSTONE_ORE;

	public function getName() : string
	{
		return "Lit Deepslate Redstone Ore";
	}

	public function getHardness() : float
	{
		return 4.5;
	}

	public function onRandomTick() : void
	{
		$this->getLevel()->setBlock($this, BlockFactory::get(BlockIds::DEEPSLATE_REDSTONE_ORE, $this->meta), false, false);
	}
}
