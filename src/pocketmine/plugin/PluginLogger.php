<?php


declare(strict_types=1);

namespace pocketmine\plugin;

use function spl_object_id;

/**
 * @phpstan-import-type LoggerAttachment from \AttachableLogger
 */
class PluginLogger extends \PrefixedLogger implements \AttachableLogger
{
	/**
	 * @var \Closure[]
	 * @phpstan-var LoggerAttachment[]
	 */
	private array $attachments = [];

	/**
	 * @phpstan-param LoggerAttachment $attachment
	 */
	public function addAttachment(\Closure $attachment)
	{
		$this->attachments[spl_object_id($attachment)] = $attachment;
	}

	/**
	 * @phpstan-param LoggerAttachment $attachment
	 */
	public function removeAttachment(\Closure $attachment)
	{
		unset($this->attachments[spl_object_id($attachment)]);
	}

	public function removeAttachments()
	{
		$this->attachments = [];
	}

	public function getAttachments()
	{
		return $this->attachments;
	}

	public function log($level, $message) : void
	{
		parent::log($level, $message);
		foreach ($this->attachments as $attachment) {
			$attachment($level, $message);
		}
	}
}
