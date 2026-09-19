<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

/**
 * List of supported compression algorithms for compressing packet batches.
 */
final class CompressionAlgorithm
{
	private function __construct()
	{
		//NOOP
	}

	public const ZLIB = 0;
	public const SNAPPY = 1;

	public const NONE = 255;
}
