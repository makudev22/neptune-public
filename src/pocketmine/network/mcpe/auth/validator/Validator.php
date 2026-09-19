<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\auth\validator;

interface Validator{
	public function validate() : ValidatorResult;
}
