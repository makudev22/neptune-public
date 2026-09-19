<?php


declare(strict_types=1);

namespace raklib;

abstract class RakLib
{
	/**
	 * Default vanilla RakNet protocol version that this library implements. Things using RakLib can override this
	 * protocol version with something different.
	 */
	public const DEFAULT_PROTOCOL_VERSION = 6;

	/** Regular RakNet uses 10 by default. MCPE uses 20. Configure this value as appropriate. */
	public static int $SYSTEM_ADDRESS_COUNT = 20;
}
