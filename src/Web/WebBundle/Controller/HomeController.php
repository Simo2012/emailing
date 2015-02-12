<?php
namespace Web\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Web\WebBundle\Form\GraphicStandards;

/**
 * Contrôleur home : page d'accueil derrière le firewall
 *
 * <pre>
 * Victor 07/02/2015 Création
 * </pre>
 * @author Victor
 * @version 1.0
 * @package Rubizz
 */
class HomeController extends Controller
{
    /**
     * Page d'accueil
     *
     * @Template()
     */
    public function indexAction()
    {
        $loManager = $this->getDoctrine()->getManager();
        $loOffers = $loManager->getRepository('WebWebBundle:Offer')->findBy(array(), array(), 6);

        return array(
            'categories' => $this->container->getParameter('web.offerCategory'),
            'offers' => $loOffers,
        );
    } // indexAction

    /**
     * Page des standards graphique
     *
     * @Template()
     */
    public function graphicsStandardsAction()
    {
        $loForm = $this->createForm(new GraphicStandards());

        return array(
            'form' => $loForm->createView()
        );
    } // graphicsStandardsAction
}
