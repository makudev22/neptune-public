<?php


declare(strict_types=1);

namespace pocketmine\scheduler;

use pocketmine\promise\PromiseResolver;
use pocketmine\Server;
use pocketmine\timings\TimingsHandler;

/**
 * @phpstan-type Resolver PromiseResolver<list<string>>
 */
final class TimingsCollectionTask extends AsyncTask
{
	/**
	 * @phpstan-param PromiseResolver<list<string>> $promiseResolver
	 */
	public function __construct(PromiseResolver $promiseResolver)
	{
		$this->storeLocal($promiseResolver);
	}

	public function onRun() : void
	{
		$this->setResult(TimingsHandler::printCurrentThreadRecords());
	}

	public function onCompletion(Server $server) : void
	{
		/**
		 * @var string[] $result
		 * @phpstan-var list<string> $result
		 */
		$result = $this->getResult();
		/**
		 * @var PromiseResolver $promiseResolver
		 * @phpstan-var PromiseResolver<list<string>> $promiseResolver
		 */
		$promiseResolver = $this->fetchLocal();

		$promiseResolver->resolve($result);
	}
}
