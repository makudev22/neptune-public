<?php


declare(strict_types=1);

namespace pocketmine\promise;

/**
 * @internal
 * @see PromiseResolver
 * @phpstan-template TValue
 */
final class PromiseSharedData
{
	/**
	 * @var \Closure[]
	 * @phpstan-var array<int, \Closure(TValue) : void>
	 */
	public array $onSuccess = [];

	/**
	 * @var \Closure[]
	 * @phpstan-var array<int, \Closure() : void>
	 */
	public array $onFailure = [];

	public ?bool $state = null;

	/** @phpstan-var TValue */
	public mixed $result;
}
