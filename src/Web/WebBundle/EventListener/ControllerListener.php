<?php
namespace Web\WebBundle\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * Event listener pour déclarer les menus
 *
 * <pre>
 * Philippe 24/04/2013 Création
 * </pre>
 * @author Philippe
 * @version 1.0
 * @package admin
 */
class ControllerListener
{
    /**
     * L'extensino twig : menu
     * @var \Natexo\AdminBundle\Twig\Extension\MenuExtension
     */
    protected $menuExtension;

    /**
     * Constructeur, injection de dépendances
     *
     * @param \Web\WebBundle\Twig\Extension\MenuExtension $poExtension
     */
    public function __construct(\Web\WebBundle\Twig\Extension\MenuExtension $poExtension)
    {
        $this->menuExtension = $poExtension;
    } // __construct

    /**
     * Event onKernelController
     *
     * @param FilterControllerEvent $poEvent
     */
    public function onKernelController(FilterControllerEvent $poEvent)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $poEvent->getRequestType()) {
            $this->menuExtension->setController($poEvent->getController());
        }
    } // onKernelController
}
