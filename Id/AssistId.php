<?php
namespace Letid\Id;
class AssistId extends AssetId
{
    public function error_get_last($Id='error_get_last')
	{
        /*
        Application::assist()->error_get_last();
        */
        if ($message = error_get_last()) {
            Application::content($Id)->set(Application::template(
                array($Id=>$message)
            ));
        }
	}
    // public function Alink($attr)
	// {
    //     return array(
	// 		'a'=> array(
	// 			'text' =>$this->Id, 'attr' => $attr
	// 		)
	// 	);
	// }
    public function new_pwd()
    {
		$chars = "abchefghjkmnpqrstuvwxyz0123456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$pwd = NULL;
		while ($i <= 9):
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pwd = $pwd . $tmp;
			$i++;
		endwhile;
		return $pwd;
	}
    /**
    * self::assist((string,optional)[ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890])->uniqid(true/false);
    * self::assist()
    * [ string $prefix = "" [, bool $more_entropy = false ]]
    * uniqid(false) -> F4518NTQTQ
    * uniqid(true) -> F451FAHSUCD90N6YNRBQHLZ9E1W
    */
    function uniqid($Id=true)
    {
        $num = hexdec((string)uniqid('LETID',$Id));
        if (!$this->Id) {
            $this->Id = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            // $this->Id = 'abchefghjkmnpqrstuvwxyz0123456789';
        }
        $base = strlen($this->Id);
        $uniqueid = '';
        for ($t = floor(log10($num) / log10($base)); $t >= 0; $t--) {
            $a = floor($num / pow($base,$t));
            $uniqueid = $uniqueid.substr($this->Id,$a,1);
            $num = $num-($a*pow($base,$t));
        }
        return $uniqueid;
    }
    /**
    * self::assist(1)->sha1(2);
    */
    public function sha1($Id)
    {
        return sha1($this->Id.$Id);
    }
    /**
     * Returns an encrypted & utf8-encoded
     * self::assist('letid')->encrypt('test');
     */
    public function encrypt($Id) {
        $create = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND);
        return mcrypt_encrypt(MCRYPT_BLOWFISH, $this->Id, utf8_encode($Id), MCRYPT_MODE_ECB, $create);
    }
    /**
     * Returns decrypted original string
     * self::assist('letid')->decrypt($mcrypt_encrypted);
     */
    public function decrypt($Id) {
        $create = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND);
        return mcrypt_decrypt(MCRYPT_BLOWFISH, $this->Id, $Id, MCRYPT_MODE_ECB, $create);
    }
    public function link($Id=null)
	{
        if ($Id) {
            return Application::html(
                array(
                    'a'=>array(
                        'text'=>$Id,
                        'attr'=>array(
                            'href'=>Application::SlA.$this->Id
                        )
                    )
                )
            );
        } else {
            return Application::SlA.$this->Id;
        }
	}
}