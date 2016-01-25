<?php
/*
Keywords Reserved!
app_initiated, app_name, app_dir
*/
define('app_initiated', microtime(true));
$loader=require __DIR__ . '/vendor/autoload.php';
// print_r($loader);die();
$Application = new App\Configuration();
// $Application->loader=$loader;
$Application->Request();
$Application->Response();
/*
Initiate HTTP_HOST, REQUEST_URI
*/

// $Project= new Lethil\Project();
// $Project->Initiate();
// App\Configuration::class
// App\Project::Initiate();
/*
Request PROCESSOR
*/
// $Initiate= new Lethil\Initiate();
// $Initiate->Request();
// App\Initiate::Request();
/*
Response the Request
*/
// $Request= new Lethil\Request();
// $Request->Response();
// App\Request::Response();
