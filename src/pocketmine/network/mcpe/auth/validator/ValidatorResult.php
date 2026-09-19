<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\auth\validator;

class ValidatorResult{

	public function __construct(
		private bool $authenticated,
		private ?string $error = null
	){ }

	public function isAuthenticated() : bool{
		return $this->authenticated;
	}

	public function getError() : ?string{
		return $this->error;
	}
}
