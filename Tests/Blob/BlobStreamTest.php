<?php

namespace WindowsAzure\DistributionBundle\Tests\Blob;

use WindowsAzure\Common\ServicesBuilder;

class BlobStreamTest extends \PHPUnit_Framework_TestCase
{
    const CONTAINER_PREFIX = 'aztest';

    protected static $path;
    protected static $uniqId;
    protected static $uniqStart;

    /**
     * Test setup
     */
    protected function setUp()
    {
        self::$path = dirname(__FILE__).'/_files/';
        date_default_timezone_set('UTC');
    }

    /**
     * Test teardown
     */
    protected function tearDown()
    {
        $blobClient = $this->createBlobClient();
        for ($i = self::$uniqStart; $i <= self::$uniqId; $i++) {
            try {
                $blobClient->deleteContainer( self::CONTAINER_PREFIX . $i);
            } catch (\Exception $e) {
            }
        }

        if (in_array('azure', stream_get_wrappers())) {
            stream_wrapper_unregister('azure');
        }
    }

    protected function createBlobClient()
    {
        if ( ! isset($GLOBALS['AZURE_BLOB_CONNECTION'])) {
            $this->markTestSkipped("Configure <php><var name=\"AZURE_BLOB_CONNECTION\" value=\"\"></php> to run the blob stream tests.");
        }

        return ServicesBuilder::getInstance()->createBlobService($GLOBALS['AZURE_BLOB_CONNECTION']);
    }

    protected function generateName()
    {
        if (self::$uniqId === null) {
            self::$uniqId = self::$uniqStart = time();
        }
        self::$uniqId++;
        return self::CONTAINER_PREFIX . self::$uniqId;
    }
    /**
     * Test read file
     */
    public function testReadFile()
    {
        $containerName = $this->generateName();
        $fileName = 'azure://' . $containerName . '/test.txt';

        $blobClient = $this->createBlobClient();
        $blobClient->registerStreamWrapper();

        $fh = fopen($fileName, 'w');
        fwrite($fh, "Hello world!");
        fclose($fh);

        $result = file_get_contents($fileName);

        $this->assertEquals('Hello world!', $result);
    }

    /**
     * Test write file
     */
    public function testWriteFile()
    {
        $containerName = $this->generateName();
        $fileName = 'azure://' . $containerName . '/test.txt';

        $blobClient = $this->createBlobClient();
        $blobClient->registerStreamWrapper();

        $fh = fopen($fileName, 'w');
        fwrite($fh, "Hello world!");
        fclose($fh);

        $instance = $blobClient->getBlobInstance($containerName, 'test.txt');
        $this->assertEquals('test.txt', $instance->Name);
    }

    /**
     * Test unlink file
     */
    public function testUnlinkFile()
    {
        $containerName = $this->generateName();
        $fileName = 'azure://' . $containerName . '/test.txt';

        $blobClient = $this->createBlobClient();
        $blobClient->registerStreamWrapper();

        $fh = fopen($fileName, 'w');
        fwrite($fh, "Hello world!");
        fclose($fh);

        unlink($fileName);

        $result = $blobClient->listBlobs($containerName);
        $this->assertEquals(0, count($result));
    }

    /**
     * Test copy file
     */
    public function testCopyFile()
    {
        $containerName = $this->generateName();
        $sourceFileName = 'azure://' . $containerName . '/test.txt';
        $destinationFileName = 'azure://' . $containerName . '/test2.txt';

        $blobClient = $this->createBlobClient();
        $blobClient->registerStreamWrapper();

        $fh = fopen($sourceFileName, 'w');
        fwrite($fh, "Hello world!");
        fclose($fh);

        copy($sourceFileName, $destinationFileName);

        $instance = $blobClient->getBlobInstance($containerName, 'test2.txt');
        $this->assertEquals('test2.txt', $instance->Name);
    }

    /**
     * Test rename file
     */
    public function testRenameFile()
    {
        $containerName = $this->generateName();
        $sourceFileName = 'azure://' . $containerName . '/test.txt';
        $destinationFileName = 'azure://' . $containerName . '/test2.txt';

        $blobClient = $this->createBlobClient();
        $blobClient->registerStreamWrapper();

        $fh = fopen($sourceFileName, 'w');
        fwrite($fh, "Hello world!");
        fclose($fh);

        rename($sourceFileName, $destinationFileName);

        $instance = $blobClient->getBlobInstance($containerName, 'test2.txt');
        $this->assertEquals('test2.txt', $instance->Name);
    }

    /**
     * Test mkdir
     */
    public function testMkdir()
    {
        $containerName = $this->generateName();

        $blobClient = $this->createBlobClient();
        $blobClient->registerStreamWrapper();

        $current = count($blobClient->listContainers());

        mkdir('azure://' . $containerName);

        $after = count($blobClient->listContainers());

        $this->assertEquals($current + 1, $after, "One new container should exist");
        $this->assertTrue($blobClient->containerExists($containerName));
    }

    /**
     * Test rmdir
     */
    public function testRmdir()
    {
        $containerName = $this->generateName();

        $blobClient = $this->createBlobClient();
        $blobClient->registerStreamWrapper();

        mkdir('azure://' . $containerName);
        rmdir('azure://' . $containerName);

        $result = $blobClient->listContainers();

        $this->assertFalse($blobClient->containerExists($containerName));
    }

    /**
     * Test opendir
     */
    public function testOpendir()
    {
        $containerName = $this->generateName();
        $blobClient = $this->createBlobClient();
        $blobClient->createContainer($containerName);

        $blobClient->putBlob($containerName, 'images/WindowsAzure1.gif', self::$path . 'WindowsAzure.gif');
        $blobClient->putBlob($containerName, 'images/WindowsAzure2.gif', self::$path . 'WindowsAzure.gif');
        $blobClient->putBlob($containerName, 'images/WindowsAzure3.gif', self::$path . 'WindowsAzure.gif');
        $blobClient->putBlob($containerName, 'images/WindowsAzure4.gif', self::$path . 'WindowsAzure.gif');
        $blobClient->putBlob($containerName, 'images/WindowsAzure5.gif', self::$path . 'WindowsAzure.gif');

        $result1 = $blobClient->listBlobs($containerName);

        $blobClient->registerStreamWrapper();

        $result2 = array();
        if ($handle = opendir('azure://' . $containerName)) {
            while (false !== ($file = readdir($handle))) {
                $result2[] = $file;
            }
            closedir($handle);
        }

        $result = $blobClient->listContainers();

        $this->assertEquals(count($result1), count($result2));
    }

    static public function dataNestedDirectory()
    {
        return array(
            array('/nested/test.txt'),
            array('/nested1/nested2/test.txt'),
        );
    }

    /**
     * @dataProvider dataNestedDirectory
     */
    public function testNestedDirectory($file)
    {
        $containerName = $this->generateName();
        $fileName = 'azure://' . $containerName . $file;

        $blobClient = $this->createBlobClient();
        $blobClient->registerStreamWrapper();

        $fh = fopen($fileName, 'w');
        fwrite($fh, "Hello world!");
        fclose($fh);

        $result = file_get_contents($fileName);

        $this->assertEquals('Hello world!', $result);
    }
}

