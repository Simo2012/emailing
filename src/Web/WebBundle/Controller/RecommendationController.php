<?php

namespace Web\WebBundle\Controller;

use Natexo\AdminBundle\Model\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Web\WebBundle\Entity\Recommendation;
use Symfony\Component\HttpFoundation\Response;

/**
 * Contrôleur recommandation : pages relatives aux recommandations de l'utilisateur
 *
 * <pre>
 * Julien 17/02/2015 Création
 * </pre>
 * @author Julien
 * @version 1.0
 * @package Rubizz
 */
class RecommendationController extends Controller
{
    /**
     * Liste des recommandations
     *
     * @Template()
     * @return array
     */
    public function indexAction(Request $poRequest)
    {
        // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();
        $loUser    = $this->getUser();

        // ==== Lecture des données ====
        $loBuilder = $loManager->getRepository('WebWebBundle:Recommendation')->getAllByUser($loUser);
        $liNbItems = 10;
        Paginator::paginate($poRequest, $liPage, $liNbItems);
        $loPaginator = new Paginator($loBuilder);
        $loPaginator->setPage($liPage);
        $loPaginator->setNbItemsPerPage($liNbItems);
        $loPaginator->setUrl($this->generateUrl('WebWebBundle_recommendationIndex'));

        return array(
            'recommendations' => $loPaginator
        );
    } // indexAction

    /**
     * Recommandation par Twitter
     *
     * @param $piOfferId
     * @param $psFrom
     * @return RedirectResponse
     */
    public function recommendByTwitterAction($piOfferId, $psFrom)
    {
        // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();
        $loUser    = $this->getUser();
        $loOffer   = $loManager->getRepository('WebWebBundle:Offer')->findOneById($piOfferId);
        $loRequest = $this->get('request_stack')->getCurrentRequest();
        $lsTweeted = $loRequest->get('tweeted');
        $lbTweeted = empty($lsTweeted) ? false : true;

        if ($lbTweeted) {
            // ==== Enregistrement du tweet ====
            $loRecommendation = $loManager->getRepository('WebWebBundle:Recommendation')->findOneBy(
                array('user' => $loUser, 'offer' => $loOffer, 'type' => 'twitter')
            );
            if (empty($loRecommendation)) {
                $loRecommendation = new Recommendation();
                $loRecommendation->setDateCreate(new \DateTime())
                                 ->setUser($loUser)
                                 ->setOffer($loOffer)
                                 ->setType('twitter');
                $loManager->persist($loRecommendation);
                $loUser->setUseTwitter(true);
                $loManager->flush();
            }
        } else {
            // ==== Construction et appel de l'url Twitter ====
            $loTwitter = $this->container->get('web.web.contact.twitter');
            $lsUrl = $loTwitter->generateUrl($loOffer);

            return $this->redirect($lsUrl);
        }

        return $this->redirect($this->generateUrl('WebWebBundle_offer'.ucfirst($psFrom)));
    } // recommendByTwitter

    /**
     * Recommendation par Facebook - Publication
     *
     * @param $piOfferId
     * @param $psFrom
     * @return string
     */
    public function recommendByFacebookAction($piOfferId, $psFrom)
    {
        // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();
        $loOffer   = $loManager->getRepository('WebWebBundle:Offer')->find($piOfferId);
        $lsLocale  = $this->get('request_stack')->getCurrentRequest()->getLocale();
        $loModelFb = $this->get('web.web.contact.facebook');
        $lsUrl     = $loModelFb->generateUrl($loOffer, $lsLocale, $psFrom);

        return $this->redirect($lsUrl);

    } // recommendByFacebookAction

    /**
     * Recommendation par Facebook - Retour de publication
     *
     * @param $piOfferId
     * @param $psFrom
     * @return string
     */
    public function addRecommendationByFacebookAction($piOfferId, $psFrom)
    {
        // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();
        $loOffer   = $loManager->getRepository('WebWebBundle:Offer')->find($piOfferId);
        $loUser    = $this->getUser();
        // ---- Récupération de l'id si soumission ----
        $lsPostId = $this->get('request_stack')->getCurrentRequest()->query->get('post_id');

        // ==== Enregistrement de la recommandation fb ====
        $loRecommendation = $loManager->getRepository('WebWebBundle:Recommendation')->findOneBy(
            array('user' => $loUser, 'offer' => $loOffer, 'type' => 'facebook')
        );
        // ---- L'utilisateur à publié sur son mur ----
        if (isset($lsPostId) && !empty($lsPostId) && empty($loRecommendation)) {
            $loRecommendation = new Recommendation();
            $loRecommendation->setUser($loUser)
                             ->setOffer($loOffer)
                             ->setType('facebook')
                             ->setDateCreate(new \DateTime);
            $loUser->setUseFacebook(true);
            $loManager->persist($loRecommendation);
            $loManager->flush();
        }
        /*$lsRoute = $psFrom == 'index' ? 'WebWebBundle_offerIndex' : 'WebWebBundle_offerList';
        return $this->redirect($this->generateUrl($lsRoute));*/
        
        return new Response("<script language='javascript'>window.close()</script>");
    } // addRecommendationByFacebookAction

    /**
     * Recommendation par email
     *
     * @param $piOfferId
     * @param $psFrom
     * @return RedirectResponse
     */
    public function recommendByEmailAction($piOfferId, $psFrom)
    {
        // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();
        $loOffer   = $loManager->getRepository('WebWebBundle:Offer')->find($piOfferId);
        $loUser    = $this->getUser();

        // ==== Enregistrement du tweet ====
        $loRecommendation = $loManager->getRepository('WebWebBundle:Recommendation')->findOneBy(
            array('user' => $loUser, 'offer' => $loOffer, 'type' => 'email')
        );
        if (empty($loRecommendation)) {
            $loRecommendation = new Recommendation();
            $loRecommendation->setDateCreate(new \DateTime())
                             ->setUser($loUser)
                             ->setOffer($loOffer)
                             ->setType('email')
                             ->setToSend(true);
            $loManager->persist($loRecommendation);
            $loUser->setUseTwitter(true);
            $loManager->flush();
        }

        return $this->redirect($this->generateUrl('WebWebBundle_offer'.ucfirst($psFrom)));
    } // recommendByEmail
}