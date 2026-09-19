<?php


declare(strict_types=1);

namespace pocketmine\lang;

class TextContainer
{
	/** @var string $text */
	protected $text;

	public function __construct(string $text)
	{
		$this->text = $text;
	}

	public function setText(string $text)
	{
		$this->text = $text;
	}

	public function getText() : string
	{
		return $this->text;
	}

	public function __toString() : string
	{
		return $this->getText();
	}
}
