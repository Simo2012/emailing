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
        // ==== Initialisation ====
        $loUser       = $this->getUser();
        $loManager    = $this->getDoctrine()->getManager();
        $loTranslator = $this->get('translator');
        $lsLocale     = $this->getRequest()->getLocale();
        
        // ==== Lecture des 6 dernieres offres ====
        $loOffers = $loManager->getRepository('WebWebBundle:Offer')->getLast(6, $lsLocale);

        // ==== Lecture des recommandations de l'utilisateur ====
        $laRecommendedOffers = $loManager->getRepository('WebWebBundle:Offer')
                                         ->getRecommendedIdsByUser($loUser);

        // ==== Calcul des gains pas mois ====
        $liAvailableAmount = $loUser->getAvailableAmount();
        $laEarningsByMonth = array();
        $laEarnings = $loManager->getRepository('WebWebBundle:PaymentRequest')->earningByMonth($loUser);
        foreach ($laEarnings as $laEarning){
            $laEarningsByMonth[$laEarning['month']] = $laEarning['amount'];
        }
        
        // ==== Gestion de la traduction des mois ====
        $laMonths = array();
        for ($liMonth = 1; $liMonth <= 12; $liMonth++) {
            $laMonths[] = $loTranslator->trans('web.web.offer.list.month.' . $liMonth);
        }

        $liUserContactsNumber = $loManager->getRepository('WebWebBundle:Contact')->getActiveNumberByUser($loUser);
        $lsEmailConfirmMessage = $loTranslator->trans(
            'web.web.offer.macro.email_confirm_message',
            array('%number%' => $liUserContactsNumber)
        );

        return array(
            'earnings'            => $laEarningsByMonth,
            'availableAmount'     => $liAvailableAmount,
            'offers'              => $loOffers,
            'recommendedOffers'   => $laRecommendedOffers,
            'months'              => $laMonths,
            'emailConfirmMessage' => $lsEmailConfirmMessage,
            'from'                => 'index'
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
        // ==== Initialisation ====
        $loUser    = $this->getUser();
        $loManager = $this->getDoctrine()->getManager();
        $lsLocale  = $this->getRequest()->getLocale();

        // ==== récupération du filtre catégorie si demandé ====
        $lsCategory = $poRequest->query->get('category');

        // ==== Lecture des offres ====
        $loOffers = $loManager->getRepository('WebWebBundle:Offer')
                              ->searchByCategory($lsCategory, $lsLocale);

        // ==== Lecture des recommandations de l'utilisateur ====
        $laRecommendedOffers = $loManager->getRepository('WebWebBundle:Offer')
                                         ->getRecommendedIdsByUser($loUser);

        return array(
            'categories'        => $this->container->getParameter('web.offerCategory'),
            'offers'            => $loOffers,
            'recommendedOffers' => $laRecommendedOffers,
            'categoryActive'    => $lsCategory,
            'from'              => 'list'
        );
    }// indexAction

}
