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
        $loBuilder = $loManager->getRepository('WebWebBundle:Recommendation')->getByUser($loUser);
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
        
        // ==== Recherche de la recommendation ====
        $loRecommendation = $loManager->getRepository('WebWebBundle:Recommendation')->findOneBy(
            array('user' => $loUser, 'offer' => $loOffer, 'type' => 'twitter')
        );

        if ($lbTweeted) {
            // ==== Modification de la recommandation ====
            if (!empty($loRecommendation)) {
                $loRecommendation->setRecommended(true);
                $loUser->setUseTwitter(true);
                $loManager->flush();
            }
        } else {
            // ==== Enregistrement du tweet ====
            if (empty($loRecommendation)) {
                $loRecommendation = new Recommendation();
                $loRecommendation->setDateCreate(new \DateTime())
                                 ->setUser($loUser)
                                 ->setOffer($loOffer)
                                 ->setType('twitter');
                $loManager->persist($loRecommendation);
                $loManager->flush();
            }
            
            // ==== Construction et appel de l'url Twitter ====
            $loTwitter = $this->container->get('web.web.model.contact.twitter');
            $lsUrl = $loTwitter->generateClickTagUrl($loOffer, $loRecommendation);
            
            return $this->redirect($lsUrl);
        }

        return $this->redirect($this->generateUrl('WebWebBundle_offer'.ucfirst($psFrom)));
    } // recommendByTwitter

    /**
     * Recommendation par Facebook - Publication
     *
     * @param $piOfferId
     * @return string
     */
    public function recommendByFacebookAction($piOfferId)
    {
        // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();
        $loOffer   = $loManager->getRepository('WebWebBundle:Offer')->find($piOfferId);
        $lsLocale  = $this->get('request_stack')->getCurrentRequest()->getLocale();
        $loUser = $this->getUser();
        
        // ==== Enregistrement de la recommandation fb ====
        $loRecommendation = $loManager->getRepository('WebWebBundle:Recommendation')->findOneBy(
            array('user' => $loUser, 'offer' => $loOffer, 'type' => 'facebook')
        );
        // ==== Création d'une recommendation ====
        if (empty($loRecommendation)) {
            $loRecommendation = new Recommendation();
            $loRecommendation->setUser($loUser)
                             ->setOffer($loOffer)
                             ->setType('facebook')
                             ->setDateCreate(new \DateTime);
            $loManager->persist($loRecommendation);
            $loManager->flush();
        }
        $loFacebook = $this->get('web.web.model.contact.facebook');
        $lsUrl      = $loFacebook->generateClickTagUrl($loOffer, $loRecommendation, $lsLocale);
        
        return $this->redirect($lsUrl);
    } // recommendByFacebookAction

    /**
     * Recommendation par Facebook - Retour de publication
     *
     * @param $piOfferId
     * @param $piRecommendationId
     * @return string
     */
    public function addRecommendationByFacebookAction($piOfferId, $piRecommendationId)
    {
        // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();
        $loOffer   = $loManager->getRepository('WebWebBundle:Offer')->find($piOfferId);
        $loUser = $this->getUser();
        
        // ---- Récupération de l'id si soumission ----
        $lsPostId = $this->get('request_stack')->getCurrentRequest()->query->get('post_id');
        // ---- L'utilisateur à publié sur son mur ----
        if (isset($lsPostId) && !empty($lsPostId) && !empty($piRecommendationId)) {
            $loRecommendation = $loManager->getRepository('WebWebBundle:Recommendation')->findOneBy(
                array('user' => $loUser, 'offer' => $loOffer, 'type' => 'facebook')
            );
            $loRecommendation->setRecommended(true);
            $loUser->setUseFacebook(true);
            $loManager->flush();
        }
        // ==== Fermeture de la popup et rechargement de la fênetre parent en JS ====
        $lsRedirect = "<script language='javascript'>window.close();window.opener.location.reload();</script>";
        return new Response($lsRedirect);
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
                             ->setToSend(true)
                             ->setRecommended(true);
            $loManager->persist($loRecommendation);
            $loUser->setUseTwitter(true);
            $loManager->flush();
        }

        return $this->redirect($this->generateUrl('WebWebBundle_offer'.ucfirst($psFrom)));
    } // recommendByEmail
}