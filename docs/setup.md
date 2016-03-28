# Setup
Once we done [initiating](docs/gettingstarted.md) our composer package, we need to [setup](docs/setup.md) and [configurate](docs/configuration.md)  our application. In this *setup* we will learn *Letid* folders and file(Namespace) structure.
Before we begin, we need 3 folders under our working directory..

1. Public (web directories/public)
2. Applications (entire apps/app)
3. Configuration (config/Route)

## Public
Public directory contain our default PHP index file and other public accessible files such as favicon, robots, htaccess. As it's received every HTTP requests, we will use PHP index file to connect our Applications configuration class. If we prefer other name than *public* there is not restriction for naming this folder.

*Preferable*: **public, www, root, html** etc...  
*default*: **public**

## Applications
The *Applications* folder will holds all of our applications in this package.

*Preferable*: **app, pro, root** etc...  
*default*: **app**

In order to change from the default *app*, we just need to add the following line in `config/Route.php` and replace $ADA value with desire name.
#### $ADA
    protected $ADA* = '../app/';

## Configuration
The *Configuration* folder has one file `Route.php` that hold the Core configuration for `$host, $dir, $config, ANS, ADR`

If we prefer other name than *config* there is not restriction for naming this folder. It's also possible that `Route.php` can be placed in our *Public* directory, however *Public* directory is not safe place for sensitive information.

*Preferable*: **config, host, common, shared, core** etc...  
*default*: **config**

#### $host
    protected $host = array(
        'localhost'=>"localhost",
        'example'=>array(
            "example.com",".example.com",".example."
        ),
        'storage-example'=>"storage.example"
    );
Application (folder) => hostname (regex without slashs)
#### $dir
    protected $dir = array(
        'template'=>'template'
    );
#### $config
    protected $config = array(
        'API_key'=>"1.2.3"
    );
#### ANS
    const ANS = __NAMESPACE__;
the Application's Namespace.
> *Note:* this can not be modified!

#### ADR
    const ADR = __DIR__;
the Application's Directory.

> *Note:* this can not be modified. Not in used (at the moment)!
