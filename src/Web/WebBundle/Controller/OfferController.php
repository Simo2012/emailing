<?php
namespace Web\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Web\WebBundle\Entity\Offer;
/**
 * Contrôleur Offer : page de gestion des offres
 *
 * <pre>
 * Elias 10/02/2015 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package Rubizz
 */
class OfferController extends Controller
{
    /**
     * Page d'accueil
     *
     * @Template()
     */
    public function indexAction()
    {
        $loManager = $this->getDoctrine()->getManager();
        $loOffers = $loManager->getRepository('WebWebBundle:Offer')->findAll();

        return array(
            'categories' => $this->container->getParameter('web.offerCategory'),
            'offers' => $loOffers,
        );
    }

// indexAction
}
