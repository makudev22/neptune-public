<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class EducationSettingsExternalLinkSettings
{
	private string $displayName;
	private string $url;

	public function __construct(string $url, string $displayName)
	{
		$this->displayName = $displayName;
		$this->url = $url;
	}

	public function getUrl() : string
	{
		return $this->url;
	}

	public function getDisplayName() : string
	{
		return $this->displayName;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$url = $in->getString();
		$displayName = $in->getString();
		return new self($displayName, $url);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putString($this->url);
		$out->putString($this->displayName);
	}
}
