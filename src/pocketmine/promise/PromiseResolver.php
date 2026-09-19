<?php


declare(strict_types=1);

namespace pocketmine\promise;

/**
 * @phpstan-template TValue
 */
final class PromiseResolver
{
	/** @phpstan-var PromiseSharedData<TValue> */
	private PromiseSharedData $shared;
	/** @phpstan-var Promise<TValue> */
	private Promise $promise;

	public function __construct()
	{
		$this->shared = new PromiseSharedData();
		$this->promise = new Promise($this->shared);
	}

	/**
	 * @phpstan-param TValue $value
	 */
	public function resolve(mixed $value) : void
	{
		if ($this->shared->state !== null) {
			throw new \LogicException("Promise has already been resolved/rejected");
		}
		$this->shared->state = true;
		$this->shared->result = $value;
		foreach ($this->shared->onSuccess as $c) {
			$c($value);
		}
		$this->shared->onSuccess = [];
		$this->shared->onFailure = [];
	}

	public function reject() : void
	{
		if ($this->shared->state !== null) {
			throw new \LogicException("Promise has already been resolved/rejected");
		}
		$this->shared->state = false;
		foreach ($this->shared->onFailure as $c) {
			$c();
		}
		$this->shared->onSuccess = [];
		$this->shared->onFailure = [];
	}

	/**
	 * @phpstan-return Promise<TValue>
	 */
	public function getPromise() : Promise
	{
		return $this->promise;
	}
}
