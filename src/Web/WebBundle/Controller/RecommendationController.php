<?php

namespace Web\WebBundle\Controller;

use Natexo\AdminBundle\Model\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
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
        $loUser = $this->getUser();
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
     * Publication sur Facebook
     *
     * @return string
     */
    public function recommendByFacebookAction($piOfferId, $psFrom)
    {
        // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();
        $loOffer = $loManager->getRepository('WebWebBundle:Offer')->find($piOfferId);
        $lsLocale = $this->getRequest()->getLocale();
        $loModelFb = $this->get('web.web.contact.facebook');
        $lsUrl = $loModelFb->generateUrl($loOffer, $lsLocale, $psFrom);

        return $this->redirect($lsUrl);

    } // recommendByFacebookAction
    
    /**
     * Retour de publication sur Facebook
     *
     * @return string
     */
    public function addRecommendationByFacebookAction($piOfferId, $psFrom = 'index')
    {
        // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();
        $loOffer = $loManager->getRepository('WebWebBundle:Offer')->find($piOfferId);
        $loUser = $this->getUser();
        // ---- Récupération de l'id si soumission ----
        $lsPostId = $this->getRequest()->query->get('post_id');

        // ---- l'user à publié sur son mur ----
        if (isset($lsPostId) && !empty($lsPostId)) {
            $loRecommendation = new Recommendation();
            $loRecommendation->setUser($loUser);
            $loRecommendation->setOffer($loOffer);
            $loRecommendation->setType('facebook');
            $loRecommendation->setDateCreate(new \DateTime);
            $loUser->setUseFacebook(true);
            $loManager->persist($loRecommendation);
            $loManager->persist($loUser);
            $loManager->flush();
        }
        
        /*$lsRoute = $psFrom == 'index' ? 'WebWebBundle_offerIndex' : 'WebWebBundle_offerList';
        return $this->redirect($this->generateUrl($lsRoute));*/
        
        return new Response("<script language='javascript'>window.close()</script>");
        
    } // addRecommendationByFacebookAction
    

}