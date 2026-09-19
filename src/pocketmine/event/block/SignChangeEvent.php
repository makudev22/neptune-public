<?php


declare(strict_types=1);

namespace pocketmine\event\block;

use InvalidArgumentException;
use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\Player;
use pocketmine\utils\Utils;

use function count;

/**
 * Called when a sign is changed by a player.
 */
class SignChangeEvent extends BlockEvent implements Cancellable
{
	/** @var Player */
	private $player;
	/** @var string[] */
	private $lines = [];

	/**
	 * @param string[] $theLines
	 */
	public function __construct(Block $theBlock, Player $thePlayer, array $theLines)
	{
		parent::__construct($theBlock);
		$this->player = $thePlayer;
		$this->setLines($theLines);
	}

	public function getPlayer() : Player
	{
		return $this->player;
	}

	/**
	 * @return string[]
	 */
	public function getLines() : array
	{
		return $this->lines;
	}

	/**
	 * @param int $index 0-3
	 *
	 * @throws InvalidArgumentException if the index is out of bounds
	 */
	public function getLine(int $index) : string
	{
		if ($index < 0 || $index > 3) {
			throw new InvalidArgumentException("Index must be in the range 0-3!");
		}

		return $this->lines[$index];
	}

	/**
	 * @param string[] $lines
	 *
	 * @throws InvalidArgumentException if there are more or less than 4 lines in the passed array
	 */
	public function setLines(array $lines) : void
	{
		if (count($lines) !== 4) {
			throw new InvalidArgumentException("Array size must be 4!");
		}
		Utils::validateArrayValueType($lines, function (string $_) : void { });
		$this->lines = $lines;
	}

	/**
	 * @param int $index 0-3
	 *
	 * @throws InvalidArgumentException if the index is out of bounds
	 */
	public function setLine(int $index, string $line) : void
	{
		if ($index < 0 || $index > 3) {
			throw new InvalidArgumentException("Index must be in the range 0-3!");
		}
		$this->lines[$index] = $line;
	}
}
