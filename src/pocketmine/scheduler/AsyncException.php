<?php


declare(strict_types=1);

namespace pocketmine\scheduler;

use Exception;

class AsyncException extends Exception
{
	/** @var AsyncExceptionData */
	protected $asyncData;

	public function __construct(AsyncExceptionData $data)
	{
		parent::__construct($data->getClass() . ": " . $data->getMessage(), $data->getCode());

		$this->asyncData = $data;

		$this->file = $data->getFile();
		$this->line = $data->getLine();
	}

	public function getAsyncData() : AsyncExceptionData
	{
		return $this->asyncData;
	}
}
