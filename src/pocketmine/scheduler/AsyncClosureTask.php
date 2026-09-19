<?php


declare(strict_types=1);

/**
 *      ___                                          _
 *    /   | ____ ___  ______ _____ ___  ____ ______(_)___  ___
 *   / /| |/ __ `/ / / / __ `/ __ `__ \/ __ `/ ___/ / __ \/ _ \
 *  / ___ / /_/ / /_/ / /_/ / / / / / / /_/ / /  / / / / /  __/
 * /_/  |_\__, /\__,_/\__,_/_/ /_/ /_/\__,_/_/  /_/_/ /_/\___/
 *          /_/
 *
 * @author - MaruselPlay
 * @link https://vk.com/maruselplay
 * @link https://github.com/MaruselPlay
 */

namespace pocketmine\scheduler;

use Closure;
use InvalidStateException;
use pocketmine\Server;
use pocketmine\thread\ThreadSafeClosure;

class AsyncClosureTask extends AsyncTask{

	public ThreadSafeClosure $closure;

	public function __construct(Closure $asyncClosure, Closure $onCompletionClosure = null){
		$this->closure = new ThreadSafeClosure($asyncClosure, $this);

		if($onCompletionClosure !== null){
			$onCompletionClosure = Closure::bind($onCompletionClosure, $this);
			$this->storeLocal($onCompletionClosure);
		}
	}

	public function onRun() : void{
		$this->closure->execute();
		unset($this->closure);
	}

	public function onCompletion(Server $server) : void{
		unset($this->closure);

		try{
			$closure = $this->fetchLocal();
		}catch(InvalidStateException $e){
			return;
		}

		if($closure === null){
			return;
		}

		($closure)($server);
		unset($closure);
	}
}
