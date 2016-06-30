<?php
namespace Letid\Assist;
/*
$timer = new Timer();
$timer->finish();
*/
abstract class Timer
{
	public function __construct()
	{
		$this->TimerStart = microtime(true);
	}
	public function restart()
    {
		$this->TimerStart = microtime(true);
	}
	public function finish()
    {
		// return (microtime(true) - $this->TimerStart);
		return $this->TimerFinish = round((microtime(true) - $this->TimerStart), 4);
	}
}