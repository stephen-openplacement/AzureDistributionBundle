<?php
/**
 * WindowsAzure DistributionBundle
 *
 * LICENSE
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@beberlei.de so I can send you a copy immediately.
 */

namespace WindowsAzureDistributionBundle\Deployment\Assets;

/**
 * Serve assets from the webrole.
 */
class WebRoleStrategy implements AssetStrategy
{
    public function deploy($documentRoot, $buildNumber)
    {
        $this->moveTo($documentRoot . DIRECTORY_SEPARATOR . "v". $buildNumber);
    }
}

