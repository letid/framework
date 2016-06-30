<?php
namespace Letid\Id;
class AssetCookie extends \Letid\Assist\Cookie
{
    public function sign()
    {
        return new self(Application::$config['signCookieId']);
        // Application::configuration('signCookieId')
    }
}
