# Configuration
It's time to work with our Application. According to our [Getting started](docs/gettingstarted.md) and [Setup](docs/setup.md), we now having

    composer.json
    composer.lock
    vendor
    public
    config
    app

in our working directory. Let's assume that we have a domain call *www.example.com* for live and we will be coding/deploying in *localhost*. It's also the time to decide what's the namespace we would like to use in our project, but for this configuration we will use `App` for our namespace.

1. Create a new PHP file `config\Route.php` and add the following code.
```php
<?php
namespace App;
use Letid\Http;
class Route extends Http\Request
{
    /*
        $host: Application (folder) => hostname (regex without slashs)
    */
    protected $host = array(
        'example'=>array(
            'example.com','.example.com','.example.', 'localhost'
        )
    );
    protected $dir = array(
		'template'=>'template',
		'language'=>'language'
	);
    protected $config = array(
        'language_default'=>'en'
    );
    /*
        ANS: the Application's Namespace!
    */
    const ANS = __NAMESPACE__;
    /*
        ADR: the Application's Directory!
    */
    const ADR = __DIR__;
}
```

2. Create a new PHP file `public\index.php` and add the following code.
```php
<?php
/*
Default document
*/
require __DIR__.'/..'.$_SERVER['PHP_SELF'];
/*
    Initiate HTTP_HOST, REQUEST_URI according to the Application's Routine!
*/
$Application = new App\Route;
/*
    Request: Http
*/
$Application->Request();
/*
    Initiate: Configuration
*/
$Application->Initiate();
/*
    Response: Http
*/
$Application->Response();
```
> *Note:* Most apache/nginx or other server that served PHP will use index.php for default document by default. If the server you are working with has different configuration, you have to follow it.

3. Now, we need to tell *Composer* to autoload our configuration directory `config` by adding the following code to *composer.json*
```
"autoload": {
    "psr-0": {
        "App": "config/"
    }
}
```
That's it for the basic and primary configuration. We now have to give a command in terminal/command prompt to generate our configuration `composer dump-autoload -o`.
Finally browse your application `http://localhost/`, if you see an error page that saying **Invalid configuration** you are on the path to success creating your application with *Letid*. *Invalid configuration* dedicate that there is no application exists in `app` directory!
> *Note:* We assume that you have installed Composer and the server pointed to public directory that we've just created.
