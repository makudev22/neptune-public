<?php


declare(strict_types=1);

namespace pocketmine\scheduler;

use pocketmine\Server;
use pocketmine\thread\NonThreadSafeValue;

class PromiseResolveTask extends AsyncTask
{
	protected \Closure $resolveFn;
	protected NonThreadSafeValue $argv;

	public function __construct(
		AsyncTaskPromise $promise
	) {
		$this->resolveFn = $promise->getClosure();
		$this->argv = new NonThreadSafeValue($promise->getArgs());

		$this->storeLocal($promise);
	}

	public function onRun() : void
	{
		try {
			$this->setResult(($this->resolveFn)(...$this->argv->deserialize()));
		} catch (\Throwable $e) {
			$this->setResult($e);
		}
	}

	public function onCompletion(Server $server) : void
	{
		$promise = $this->fetchLocal();

		if (($result = $this->getResult()) === null) {
			$promise->resolve(null);
			return;
		}

		if ($result instanceof \Throwable) {
			$promise->crash(new AsyncException(new AsyncExceptionData($result)));
			return;
		}

		if ($result instanceof NonThreadSafeValue) {
			$result = $result->deserialize();
		}

		$promise->resolve($result);
	}
}
