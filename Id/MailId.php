<?php
namespace Letid\Id;
/**
* $to = "somebody@example.com";
* $subject = "My subject";
* $message = "Hello world!";
* $headers = "From: webmaster@example.com" . "\r\n" . "CC: somebodyelse@example.com";
* mail($to,$subject,$message,$headers);
* self::mail('message')->to()->subject()->header()->send();
*/
class MailId extends AssetId
{
    private $recipient, $subject, $header, $option;
    // public function subject($subject)
    // {
    //     if ($subject) {
    //         $this->subject = $subject;
    //     }
    //     return $this;
    // }
    /**
    * MIME-Version: 1.0 \r\n
    * Content-type: text/html;charset=UTF-8 \r\n
    * From: noreply@mail.com \r\n
    * CC: carbon_copy@mail.com \r\n
    */
    public function header($header='From: noreply@lethil.me')
    {
        if ($header) {
            if (is_array($header)) {
                $this->header = Application::arrays($header)->key_join_value("\r\n",'%s: %s');
            } else {
                $this->header = $header;
            }
        }
        return $this;
    }
    public function to($recipient)
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
        return mail($this->recipient,$this->subject,Application::template($this->Id),$this->header,$this->option);
        // return $this->message();
    }
}