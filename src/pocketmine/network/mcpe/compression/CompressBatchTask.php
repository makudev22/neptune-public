<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\compression;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class CompressBatchTask extends AsyncTask
{
	/** @var string */
	protected $data;
	/** @var int */
	protected $protocol;
	/** @var int */
	protected $level = 7;

	public function __construct(string $data, int $protocol, int $compressionLevel, CompressBatchPromise $promise)
	{
		$this->data = $data;
		$this->protocol = $protocol;
		$this->level = $compressionLevel;

		$this->storeLocal($promise);
	}

	public function onRun()
	{
		$this->setResult(NetworkCompression::compress($this->data, $this->protocol, $this->level));
	}

	public function onCompletion(Server $server)
	{
		$promise = $this->fetchLocal();

		if ($promise instanceof CompressBatchPromise) {
			$promise->resolve($this->getResult());
		}
	}
}
