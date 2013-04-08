---
layout: default
title: Deloyment on Windows Azure Websites
---

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

Call the following command in your project:

    php src/console azure:websites:init

You should modify the copied ".deployment" and "build_azure.sh" files to your needs,
for example you can add calls to ``php app/console doctrine:schema-tool:update --force``
if you want to auto update your database schema.

Commit the changes to all files to your Azure Git repository:

    $ git add build_azure.sh .deployment
    $ git commit -m 'Enable Azure Websites Deployment'

## Deloyment

Whenever you push to your git repository now to the Azure Websites location,
Kudo (the Git Deployment Engine of Azure Websites) will trigger the custom
build command.

## Troubleshotting

### The website build failed, what now?

If the failure didnt happen during the kudu sync your website shouldn't be broken.
You can just hit the "retry" button in the Windows Azure Management backend and deploy again.
Should the failure happen during the kudu sync then your website might be in a broken state.
Try to redeploy as soon as you can to fix potential problems.

### A command failed during deployment, what now?

You should carefully analyze what commands you run in the ``build_azure.sh`` file.
There is no interaction possible and you should take care that your website always
runs and no build step breaks it.
