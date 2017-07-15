<?php
namespace letId\assist
{
	/*
	$timer = new timer();
	$timer->finish();
	*/
	abstract class timer
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
}