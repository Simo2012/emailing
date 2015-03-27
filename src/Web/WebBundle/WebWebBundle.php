<?php
namespace Web\WebBundle;

use Web\WebBundle\Model\Tracking\TrackingCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WebWebBundle extends Bundle
{
    /**
     * *(non-PHPdoc)
     * @see \Symfony\Component\HttpKernel\Bundle\Bundle::build()
     * @param ContainerBuilder $poContainer
     */
    public function build(ContainerBuilder $poContainer)
    {
        parent::build($poContainer);

        $poContainer->addCompilerPass(new TrackingCompilerPass());
    } // build
}
