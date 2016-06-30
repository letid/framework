<?php
namespace Letid\Id;
abstract class Initiate extends Core
{
	public function InitiateRequest()
	{
		// print_r(Application::cookie()->sign()->get(true));
		if (isset($_GET['signout'])) {
			$this->InitiateReset();
		} else {
			// Application::cookie()->sign()->get(true)
			// AssetCookie::sign()->get(true)
			if ($signCookieValue = Application::cookie()->sign()->get(true)) {
				$this->InitiateUser($signCookieValue);
			}
		}
	}
	private function InitiateUser($signCookieValue)
    {
        $user = Application::$database->select('*')->from($this->table['user'])->where($signCookieValue)->execute()->toObject()->rowsCount();
		if ($user->rowsCount) {
			Application::$user = $this->user = $user->rows;
			Application::content('display.name')->set($user->rows->displayname);
		} else {
			$this->TerminalReset();
		}
    }
    private function InitiateDatabase()
    {
        // return new Id\Database;
    }
    private function InitiateReset()
    {
		// TODO: Not all session should be removed
		// session_unset();
		Application::session()->remove();
		// session_unset($_SESSION[$hostname]);
		// AssetCookie::sign()->remove();
		// Application::cookie(Application::configuration('signCookieId'))->remove();
		// AssetCookie::sign()->remove();
		Application::cookie()->sign()->remove();
		// Application::cookie(Application::$config['signCookieId'])->sign()->remove();
    }
}
