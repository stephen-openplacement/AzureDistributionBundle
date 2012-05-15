---
layout: default
title: Quickstart
---

# Quickstart

This quickstart will guide you through the steps to deploy a clean Symfony2 application on Windows Azure. This will contain the AcmeDemoBundle that has a very simple hello world page.

## Using a downloadable Symfony version

1. Go to symfony.com/download and download the latest version with vendors. (Currently http://symfony.com/download?v=Symfony_Standard_Vendors_2.0.13.zip)

2. Unzip the archive into a directory.

3. Create a new subdirectory vendor\bundles\WindowsAzure\DistributionBundle

4. Download the WindowsAzure Distribution Bundle (+dependencies) from https://github.com/beberlei/AzureDistributionBundle/downloads The file is called `windows-azure-distribution-with-dependencies-v*.zip` where the star can be replaced by some version. Pick the biggest version.

5. Unzip the file and copy the contents into the vendor/azure folder

6. Modify the app/autoload.php file to include the following lines in the array inside the `registerNamespaces()` method:

        'WindowsAzure\\DistributionBundle'  => __DIR__ . '/../vendor/azure/',
        'WindowsAzure\\TaskDemoBundle'      => __DIR__ . '/../vendor/azure/',
        'Beberlei\\AzureBlobStorage'        => __DIR__ . '/../vendor/azure/azure-blob-storage/lib/',
        'Doctrine\\Shards'                  => __DIR__ . '/../vendor/azure/doctrine-shards/lib/',
        'Doctrine\\KeyValueStore'           => __DIR__ . '/../vendor/azure/doctrine-keyvaluestore/lib/',

7. Modify the app/AppKernel.php to include `new WindowsAzure\DistributionBundle\WindowsAzureDistributionBundle()` in the $bundles array. Also replace the `extends Kernel` with `extends AzureKernel` and add a new import statement to the top of the file `use WindowsAzure\DistributionBundle\HttpKernel\AzureKernel;`. Details of this step are described in the README.md of this project under the topic "Azure Kernel".

8. Open up the terminal and go to the project root. Call "php app\console". You should see a list of commands, containing two of the windows azure commands at the bottom:

        windowsazure:init
        windowsazure:package

9. Call `php app\console windowsazure:init`

10. Install the Azure TaskDemoBundle (optional) to see some of the features of Azure in a Demo application. See the section blow for a step by step introduction for this bundle.

11. Call `php app\console windowsazure:package`

12. Deploy the `build\ServiceDefinition.cscfg` and `build\azure-1.cspkg` using the management console

13. Browse to http://appid.cloudapp.net/ - http://appid.cloudapp.net/hello/world or http://appid.cloudapp.net/tasks

## Installing the Task Demo Bundle

1. Add `new WindowsAzure\TaskDemoBundle\WindowsAzureTaskDemoBundle()` into the `$bundles` array in `app\AppKernel.php`
2. Configure the database by modifying `app\config\azure_parameters.yml`.

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

3. Configure Security

    Open `app\config\security.yml` and exchange the line:

        - { resource: security.yml }

    with the following line (careful with indention and make sure to use spaces, not tabs): 

        - { resource: ../../vendor/azure/WindowsAzure/TaskDemoBundle/Resources/config/security.yml }

4. Register routes in app\config\routing.yml

        WindowsAzureTaskDemoBundle:
            resource: "@WindowsAzureTaskDemoBundle/Controller/"
            type:     annotation
            prefix:   /


5. Import the contents of the "schema.sql" from vendor\azure\WindowsAzure\TaskDemoBundle\Resources\schema.sql into your SQL Azure database.
