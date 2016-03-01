<?php
namespace Letid\Http;
use Letid\Id;
abstract class Initiate extends Response
{
	use Id\Http, Id\Initiate, Id\Cluster, Id\Template;
	// public function Request()
	// {
	// }
	// public function Initiate()
	// {
	// }
	// public function Response()
	// {
	// }
}
