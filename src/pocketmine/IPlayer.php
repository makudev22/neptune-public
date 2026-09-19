<?php


declare(strict_types=1);

namespace pocketmine;

use pocketmine\permission\ServerOperator;

interface IPlayer extends ServerOperator
{
	public function isOnline() : bool;

	public function getName() : string;

	public function getLowerCaseName() : string;

	public function isBanned() : bool;

	/**
	 * @return void
	 */
	public function setBanned(bool $banned);

	public function isWhitelisted() : bool;

	/**
	 * @return void
	 */
	public function setWhitelisted(bool $value);

	/**
	 * @return Player|null
	 */
	public function getPlayer();

	public function getFirstPlayed() : int;

	public function getLastPlayed() : int;

	public function hasPlayedBefore() : bool;

}
