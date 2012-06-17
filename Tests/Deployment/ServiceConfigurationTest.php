<?php
namespace WindowsAzure\DistributionBundle\Tests\Deployment;

use WindowsAzure\DistributionBundle\Deployment\ServiceConfiguration;

class ServiceConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function createServiceConfiguration()
    {
        $file = __DIR__ . '/_files/ServiceConfiguration.csdef';
        copy($file, $file . ".work");

        return new ServiceConfiguration($file . ".work");
    }

    public function testSetConfiguration()
    {
        $sc = $this->createServiceConfiguration();
        $sc->setConfigurationSetting("Sf2.Web", "Key", "value");

        $this->assertContains('<Setting name="Key" value="value"/>', $sc->getXml());
    }

    public function testSetConfigurationExitingOverwritten()
    {
        $sc = $this->createServiceConfiguration();
        $sc->setConfigurationSetting("Sf2.Web", "Key", "value");
        $sc->setConfigurationSetting("Sf2.Web", "Key", "value2");

        $this->assertNotContains('<Setting name="Key" value="value"/>', $sc->getXml());
        $this->assertContains('<Setting name="Key" value="value2"/>', $sc->getXml());
    }
}

