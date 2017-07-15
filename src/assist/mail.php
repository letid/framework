<?php
namespace letId\assist
{
	/*
	self::mail('message')
		->to()
		->subject()
		->header()
		->send();
		
	avail::mail('message')->to()->subject()->header()->send();
	*/
	/**
	* $to = "somebody@example.com";
	* $subject = "My subject";
	* $message = "Hello world!";
	* $headers = "From: webmaster@example.com" . "\r\n" . "CC: somebodyelse@example.com";
	* mail($to,$subject,$message,$headers);
	* self::mail('message')->to()->subject()->header()->send();
	*/
	abstract class mail extends essential
	{
		private $recipient, $subject, $header, $option;
		public function test()
		{
  		echo 'letId\assist\mail('.$this->Id.')';
  	}
    public function subject($subject=null)
    {
      if ($subject) {
        $this->subject = $subject;
      }
      return $this;
    }
    /**
    * MIME-Version: 1.0 \r\n
    * Content-type: text/html;charset=UTF-8 \r\n
    * From: noreply@mail.com \r\n
    * CC: carbon_copy@mail.com \r\n
    */
    public function header($header='From: noreply@lethil.me')
    {
			$this->header = $header;
      // if (is_array($header)) {
      //     $this->header = self::arrays($header)->key_join_value("\r\n",'%s: %s');
      // } else {
      //     $this->header = $header;
      // }
      return $this;
    }
    public function to($recipient=null)
    {
      if ($recipient) {
        if (is_array($recipient)) {
          $this->recipient = implode(', ',$recipient);
        } else {
          $this->recipient = $recipient;
        }
      }
      return $this;
    }
    public function option($option)
    {
      $this->option = $option;
      return $this;
    }
    public function send()
    {
      return mail($this->recipient,$this->subject,$this->Id,$this->header,$this->option);
      // return mail($this->recipient,$this->subject,self::template($this->Id),$this->header,$this->option);
    }
	}
}