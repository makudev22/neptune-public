<?php


declare(strict_types=1);

namespace pocketmine\block;

use InvalidArgumentException;
use pocketmine\math\Facing;

class Rail extends BaseRail
{
	/* extended meta values for regular rails, to allow curving */
	public const CURVE_SOUTHEAST = 6;
	public const CURVE_SOUTHWEST = 7;
	public const CURVE_NORTHWEST = 8;
	public const CURVE_NORTHEAST = 9;

	private const CURVE_CONNECTIONS = [
		self::CURVE_SOUTHEAST => [
			Facing::SOUTH,
			Facing::EAST
		],
		self::CURVE_SOUTHWEST => [
			Facing::SOUTH,
			Facing::WEST
		],
		self::CURVE_NORTHWEST => [
			Facing::NORTH,
			Facing::WEST
		],
		self::CURVE_NORTHEAST => [
			Facing::NORTH,
			Facing::EAST
		]
	];

	protected $id = self::RAIL;

	public function getName() : string
	{
		return "Rail";
	}

	protected function getMetaForState(array $connections) : int
	{
		try {
			return self::searchState($connections, self::CURVE_CONNECTIONS);
		} catch (InvalidArgumentException $e) {
			return parent::getMetaForState($connections);
		}
	}

	protected function getConnectionsForState() : array
	{
		return self::CURVE_CONNECTIONS[$this->meta] ?? self::CONNECTIONS[$this->meta];
	}

	protected function getPossibleConnectionDirectionsOneConstraint(int $constraint) : array
	{
		/** @var int[] $horizontal */
		static $horizontal = [
			Facing::NORTH,
			Facing::SOUTH,
			Facing::WEST,
			Facing::EAST
		];

		$possible = parent::getPossibleConnectionDirectionsOneConstraint($constraint);

		if (($constraint & self::FLAG_ASCEND) === 0) {
			foreach ($horizontal as $d) {
				if ($constraint !== $d) {
					$possible[$d] = true;
				}
			}
		}

		return $possible;
	}
}
