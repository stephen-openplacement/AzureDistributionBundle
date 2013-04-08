---
layout: default
title: Deloyment on Windows Azure Websites
---

# Deloyment on Windows Azure Websites

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

- [Installation of AzureDistributionBundle](http://beberlei.github.io/AzureDistributionBundle/installation.html)
- [Azure Website with MySQL And Git](http://www.windowsazure.com/en-us/develop/php/tutorials/website-w-mysql-and-git/)

## Preparing

Call the following command in your project:

    php src/console azure:websites:init

This creates two files ".deployment" and "build_azure.sh" into the root of your project.

You should modify the copied ".deployment" and "build_azure.sh" files to your needs,
for example you can add calls to ``php app/console doctrine:schema-tool:update --force``
if you want to auto update your database schema.

Commit the files to your Azure Git repository:

    $ git add build_azure.sh .deployment
    $ git commit -m 'Enable Azure Websites Deployment'

You also need to download the latest version of [Composer](http://getcomposer.org/)
and commit the ``composer.phar`` file into your Git repository.

## Configuration

You can either commit the ``parameters.yml`` with all the production data to your
Azure Git repository or use the [external parameters feature](http://symfony.com/doc/2.1/cookbook/configuration/external_parameters.html)
to set the configuration variables in the Windows Azure Management console.

Go to your website, "Configure" and then "app settings". Enter the environment
variables there following the ``SYMFONY__`` pattern. Dots in the variable
names of your ``parameter.yml`` translate to two underscores (``__``).

<img src="http://beberlei.github.io/AzureDistributionBundle/assets/env.png" />

## Deloyment

Whenever you push to your git repository now to the Azure Websites location,
Kudo (the Git Deployment Engine of Azure Websites) will trigger the custom
build command.

    $ git push azure master

## Troubleshooting

### The website build failed, what now?

If the failure didnt happen during the kudu sync your website shouldn't be broken.
You can just hit the "retry" button in the Windows Azure Management backend and deploy again.
Should the failure happen during the kudu sync then your website might be in a broken state.
Try to redeploy as soon as you can to fix potential problems.

### A command failed during deployment, what now?

You should carefully analyze what commands you run in the ``build_azure.sh`` file.
There is no interaction possible and you should take care that your website always
runs and no build step breaks it.
