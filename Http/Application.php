<?php
namespace Letid\Http;
use Letid\Config;
abstract class Application extends Request
{
	use Config\Http, Config\Initiate, Config\Page, Config\Template;
	public function __construct()
    {
        /*
        constructor!
        */
    }
}
