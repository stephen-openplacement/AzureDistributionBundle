---
layout: default
title: Quickstart
---

# Quickstart

This quickstart will guide you through the steps to deploy a clean Symfony2 application on Windows Azure. This will contain the AcmeDemoBundle that has a very simple hello world page.

## Using a downloadable Symfony version

1. Go to `https://github.com/beberlei/AzureDistributionBundle/downloads`. Download the latest `symfony-azure-distribution-v*.zip` file. This is a modified Symfony Standard Distribution including all necessary bundles and libraries for Windows Azure and  modified `app\autoload.php` and `app\AppKernel.php` files. Unzip this archive to a directory of your choice.

2. Open up the terminal and go to the project root. Call "php app\console". You should see a list of commands, containing two of the windows azure commands at the bottom:

        windowsazure:init
        windowsazure:package

3. Call `php app\console windowsazure:init`. This creates a bunch of files in your project.

4. Configure the database by modifying `app\config\azure_parameters.yml`.

    An example of the parameters.yml looks like:

        # Put Azure Specific configuration parameters into
        # this file. These will overwrite parameters from parameters.yml
        parameters:
            session_type: pdo
            database_driver: pdo_sqlsrv
            database_host: tcp:DBID.database.windows.net
            database_user: USER@DBID
            database_password: PWD
            database_name: DBNAME

5. Configure Security

    Open `app\config\security.yml` and exchange the line:

        - { resource: security.yml }

    with the following line (careful with indention and make sure to use spaces, not tabs):

        - { resource: ../../vendor/azure/WindowsAzure/TaskDemoBundle/Resources/config/security.yml }

6. Register routes in app\config\routing.yml

        WindowsAzureTaskDemoBundle:
            resource: "@WindowsAzureTaskDemoBundle/Controller/"
            type:     annotation
            prefix:   /

7. Configure Sharding options:

    windows_azure_distribution:
        # append to existing config
        federations:
            default:
                federationName: User_Federation
                distributionKey: user_id
                distributionType: guid


8. Call `php app\console windowsazure:package` which creates two files into the `build` directory of your project.

9. Deploy the `build\ServiceDefinition.cscfg` and `build\azure-1.cspkg` using the management console

10. Import the contents of the "schema.sql" from vendor\azure\WindowsAzure\TaskDemoBundle\Resources\schema.sql into your SQL Azure database.

11. Browse to http://appid.cloudapp.net/ - http://appid.cloudapp.net/hello/world or http://appid.cloudapp.net/tasks

## Logging

To get error logging working see the [Logging chapter](10_logging.md) of this documentation.
