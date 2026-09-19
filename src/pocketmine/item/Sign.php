<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\SignPost;

class Sign extends Item
{
	public SignPost $signPost;

	public function __construct(int $id, int $meta, string $name, SignPost $signPost)
	{
		parent::__construct($id, $meta, $name);
		$this->signPost = $signPost;
	}

	public function getBlock() : Block
	{
		return $this->signPost;
	}

	public function getMaxStackSize() : int
	{
		return 16;
	}
}
