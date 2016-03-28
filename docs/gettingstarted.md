# Getting Started
You probably wanted to skip *Getting Started* as we focus on the basic initiation of Composer Package.
However, you may wanted to noticed that **letid/framework** should be add and require in composer.json.
```
"require": {
    "letid/framework": "dev-master"
}
```
or for initiated Packages
```
php composer.phar require --prefer-dist letid/framework
```
## Let's initiate Package
```
composer init

Package name(vendor/Name): home/app
Description: Application description  
Author: my Name  
Minimum Stability: dev  
Package Type: applications  
License: MIT  

Would you like to define your dependencies (require) interactively [yes]: yes  
Search for a package: letid  
Found 2 packages matching letid

[0] letid/lethil  
[1] letid/framework

Enter package # to add, or the complete package name if it is not listed: 1
Would you like to define your dependencies (require) interactively [yes]: no

{
    "name": "home/app",
    "description": "Application description",
    "type": "application",
    "require": {
        "letid/framework": "^1.0"
    },
    "license": "MIT",
    "authors": [
        {
            "name": "my Name",
            "email": "my@email.com"
        }
    ],
    "minimum-stability": "dev"
}

Do you confirm generation [yes]: yes
```
That's it, we are done with basic initiating our composer package! We have noticed that in our working directory a file called *composer.json* was created!

## Let's update Package
We have just created the basic configuration for Package. it's tell what your package is all about and what are the requirement. Now we just need to give a command line to download.
```
composer update
```
Again in our working directory, there is a folder called *vendor* was created. The *vendor* folder stored all our dependencies package and we may not need to backup or push this folder as we have sat our requirement in the configuration *composer.json*. Everytime we *composer update* composer will download all the dependencies if not downloaded. If downloaded and found a newer version it will automatically update to a newer version. This is the basic introduction of how *composer* can be flexible and convenient to our project as it's remove manual tasks such as dependencies install/update/remove.

## How do we rename *vendor* folder?
In come cases we may wanted to rename the *vendor* folder. To do so we just need to add the following lines in *composer.json* and replace the vendor-dir's value with desire folder name.
```
"config": {
    "vendor-dir": "vendor"
}
```
Finally we are done initiating composer package with it's dependencies. Now it's the time to [Setup](docs/setup.md) our Application.
