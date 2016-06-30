<?php
namespace Letid\Id;
class AssetSession extends AssetId
{
    public function id()
    {
        // return $this->Id.Application::configuration('version')->get();
        return $this->Id.Application::$config['version'];
    }
    public function start()
    {
        if ($this->status() === false) session_start();
    }
    private function status()
    {
        if (php_sapi_name() !== 'cli') {
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                return session_status() === PHP_SESSION_ACTIVE ? true : false;
            } else {
                return session_id() === '' ? false : true;
            }
        }
        return false;
    }
    public function remove()
    {
        session_unset();
    }
}
