<?php


declare(strict_types=1);

namespace pocketmine\scheduler;

use pocketmine\timings\TimingsHandler;

final class TimingsControlTask extends AsyncTask
{
	private const ENABLE = 1;
	private const DISABLE = 2;
	private const RELOAD = 3;

	private function __construct(
		private int $operation
	) {
	}

	public static function setEnabled(bool $enable) : self
	{
		return new self($enable ? self::ENABLE : self::DISABLE);
	}

	public static function reload() : self
	{
		return new self(self::RELOAD);
	}

	public function onRun() : void
	{
		if ($this->operation === self::ENABLE) {
			TimingsHandler::setEnabled(true);
			\GlobalLogger::get()->debug("Enabled timings");
		} elseif ($this->operation === self::DISABLE) {
			TimingsHandler::setEnabled(false);
			\GlobalLogger::get()->debug("Disabled timings");
		} elseif ($this->operation === self::RELOAD) {
			TimingsHandler::reload();
			\GlobalLogger::get()->debug("Reset timings");
		} else {
			throw new \InvalidArgumentException("Invalid operation $this->operation");
		}
	}
}
