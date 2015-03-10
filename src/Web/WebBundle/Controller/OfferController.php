<?php
namespace Web\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Web\WebBundle\Entity\Offer;
use Web\WebBundle\Form\GraphicStandards;

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
     * Page d'accueil des offres
     *
     * @Template()
     */
    public function indexAction()
    {
        $loUser = $this->getUser();
        $loManager = $this->getDoctrine()->getManager();
        // recuperer les 6 dernieres offres
        $loOffers = $loManager->getRepository('WebWebBundle:Offer')->findBy(
            array(),
            array('dateCreate' => 'desc'),
            6,
            0
        );
        $liAvailableAmount = $loUser->getAvailableAmount();
        $laEarningsByMonth = array();
        // calculer les gains par mois
        $laEarnings = $loManager->getRepository('WebWebBundle:PaymentRequest')->earningByMonth($loUser);
        foreach ($laEarnings as $laEarning){
            $laEarningsByMonth[$laEarning['month']] = $laEarning['amount'];
        }

        return array(
            'earnings'        => $laEarningsByMonth,
            'availableAmount' => $liAvailableAmount,
            'offers'          => $loOffers,
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


    /**
     * Page toutes les offres
     *
     * @Template()
     */
    public function listAction(Request $poRequest)
    {
        $loManager = $this->getDoctrine()->getManager();
        //récupération du filtre catégorie si demandé
        $lsCategory = $poRequest->query->get('category');

        $loOffers = $loManager->getRepository('WebWebBundle:Offer')->searchByCategory($lsCategory);

        return array(
            'categories' => $this->container->getParameter('web.offerCategory'),
            'offers' => $loOffers,
        );
    }// indexAction

}
