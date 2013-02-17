# Deloyment on Windows Azure Websites

NOTE: This feature is still in development.

With the June 2012 release Windows Azure includes Websites that allow you deploy projects
from Git. It is much easier to deploy to than the Azure Cloud Services platform, which
requires a lot of work on the build and deployment process.

You can use the **AzureDistributionBundle** to deploy your Symfony2 application to
WindowsAzure Websites. Composer dependencies will be installed on the Azure Websites
platform during the deloyment, you don't have to check them into your Git repository.

This guide explains:

1. Preparing your Symfony2 project for deployment on Azure Websites.
2. Deployment of a Symfony2 project on Azure Websites.

This guide requires you to have setup an Azure Website and configured
it to work with Git Deployment.

- [Azure Website with MySQL And Git](http://www.windowsazure.com/en-us/develop/php/tutorials/website-w-mysql-and-git/)

## Preparing

Create a file called ``.deployment`` in the root directory of your project with the
following contents:

    [config]
    command = "D:\Program Files (x86)\PHP\v5.3\php.exe" build_azure.php

If you are using PHP 5.4 in your Azure Website project, then use the following instead:

    [config]
    command = "D:\Program Files (x86)\PHP\v5.4\php.exe" build_azure.php

Create a second file called ``build_azure.php`` with the following contents:

```php
<?php
if ( ! file_exists("composer.phar")) {
    downloadComposer();
}

$_SERVER['argv'][1] = "install";
$_SERVER['argv'][2] = "--prefer-dist";
$_SERVER['argv'][3] = "-v";
require "composer.phar";

function downloadComposer()
{
    $url = 'https://getcomposer.org/composer.phar';
    file_put_contents("composer.phar", file_get_contents($url));
}
```

Now modify your ``composer.json`` file to include a post install/update task:

    {
        "post-install-cmd": [
            "WindowsAzure\DistributionBundle\Deployment\Composer\DeploymentListener::postInstall"
        ],
        "post-update-cmd": [
            "WindowsAzure\DistributionBundle\Deployment\Composer\DeploymentListener::postInstall"
        ]
    }

The file might already hold the ``post-install-cmd`` and ``post-update-cmd`` keys. In that case
append the two script callback values to the list of existing callbacks.

Commit the changes to all three files to your Azure Git repository:

    $ git add build_azure.php .deployment composer.json
    $ git commit -m 'Enable Azure Websites Deployment'

## Deloyment

Whenever you push to your git repository now to the Azure Websites location,
Kudo (the Git Deployment Engine of Azure Websites) will trigger the custom
build command and:

1. Download composer.phar if not yet present. If you want to use a stable
version, you should commit the ``composer.phar`` into the root of your project
directory.
2. Run ``php composer.phar install --prefer-dist -v``
3. Run the deployment listener and copy all files into the Azure Websites web root.

