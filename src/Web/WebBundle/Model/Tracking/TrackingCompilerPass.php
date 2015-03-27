<?php
namespace Web\WebBundle\Model\Tracking;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compilation du paramétrage des modèle de lecture des stats
 *
 * <pre>
 * Julien 26/03/2015 Création
 * </pre>
 * @author Julien
 * @version 1.0
 * @package Web
 */
class TrackingCompilerPass implements CompilerPassInterface
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface::process()
     * @param ContainerBuilder $poContainer
     */
    public function process(ContainerBuilder $poContainer)
    {
        if (!$poContainer->hasDefinition('web.web.model.tracking.tracking_chain')) {
            return;
        }
        $loDefinition = $poContainer->getDefinition('web.web.model.tracking.tracking_chain');
        $laTaggedServices = $poContainer->findTaggedServiceIds('offer-tracking');
        foreach ($laTaggedServices as $lsId => $laTagAttributes) {
            foreach ($laTagAttributes as $laAttributes) {
                $loDefinition->addMethodCall(
                    'addModel',
                    array(new Reference($lsId), $laAttributes['alias'])
                );
            }
        }
    } // process
}
