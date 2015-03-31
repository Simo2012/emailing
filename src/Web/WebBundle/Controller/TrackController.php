<?php
namespace Web\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Contrôleur track : gestion des tags de tracking
 *
 * <pre>
 * Philippe 13/02/2015 Création
 * </pre>
 * @author Philippe
 * @version 1.0
 * @package Rubizz
 */

class TrackController extends Controller
{
    /**
     * Tag d'ouverture
     */
    public function openAction(Request $poRequest)
    {
        // ==== Initialisation ====
        $liRecommendationId = $poRequest->get('piRecommendationId');
        $lsEmail = $poRequest->get('email');
        $loResponse = $this->get('web.web.model.response.empty_img_response')->get();
        $loTracking = $this->get('web.web.model.tracking.tracking');
        if (!$loTracking->readRecommendation($liRecommendationId)) {
            trigger_error('OpenTag la recommendation n\'existe pas');
            return $loResponse;
        }

        // ==== Prise en compte du tracking ====
        $loTracking->handleOpenTag($lsEmail);
        $loTracking->writeCookies($loResponse, $lsEmail);

        return $loResponse;
    } // openAction

    /**
     * Tag de clic
     */
    public function clickAction(Request $poRequest)
    {
        // ==== Initialisation ====
        $liRecommendationId = $poRequest->get('piRecommendationId');
        $lsEmail = $poRequest->get('email');
        $loTracking = $this->get('web.web.model.tracking.tracking');
        if (!$loTracking->readRecommendation($liRecommendationId)) {
            trigger_error('ClickTag la recommendation n\'existe pas');
            $loResponse = new Response();
            return $loResponse;
        }
        // ==== Détection d'une mauvaise ip par rapport à l'offre (geoip) ====
        $lbIsValidCountryByIp = $loTracking->chekCountryByIp($liRecommendationId);
        if (!$lbIsValidCountryByIp) {
            return $this->redirect($this->generateUrl('WebWebBundle_defaultLogoutErrorIp'));
        }
        // ==== Prise en compte du tracking ====
        $loTracking->handleClickTag($lsEmail);
        $loResponse = new RedirectResponse($loTracking->getClickUrl());
        $loTracking->writeCookies($loResponse, $lsEmail);

        return $loResponse;
    } // clickAction

    /**
     * Tag de lead
     */
    public function leadAction(Request $poRequest)
    {
        // ==== Initialisation ====
        $liOfferId = $poRequest->get('operation');
        $lsTransaction = $poRequest->get('transaction');
        $loResponse = $this->get('web.web.model.response.empty_img_response')->get();
        if (empty($liOfferId) || empty($lsTransaction)) {
            trigger_error('LeadTag paramètres incorrects');
            return $loResponse;
        }
        $loTracking = $this->get('web.web.model.tracking.tracking');
        
        // ==== Détection d'une mauvaise ip par rapport à l'offre (geoip) ====
        $lbIsValidCountryByIp = $loTracking->chekCountryOfferByIp($liOfferId);
        if (!$lbIsValidCountryByIp) {
            return $this->redirect($this->generateUrl('WebWebBundle_defaultLogoutErrorIp'));
        }
        
        if (!$loTracking->readCookies($poRequest)) {
            trigger_error('LeadTag cookies inexistant');
            return $loResponse;
        }

        // ==== Prise en compte du tracking ====
        $loTracking->handleLeadTag($lsTransaction);

        return $loResponse;
    } // leadAction

    /**
     * Tag d'achat
     */
    public function saleAction(Request $poRequest)
    {
        // ==== Initialisation ====
        $liOfferId = $poRequest->get('operation');
        $lsTransaction = $poRequest->get('transaction');
        $lsAmount = $poRequest->get('amount');
        $loResponse = $this->get('web.web.model.response.empty_img_response')->get();
        if (empty($liOfferId) || empty($lsTransaction)) {
            trigger_error('SaleTag paramètres incorrects');
            return $loResponse;
        }
        $loTracking = $this->get('web.web.model.tracking.tracking');
        
        // ==== Détection d'une mauvaise ip par rapport à l'offre (geoip) ====
        $lbIsValidCountryByIp = $loTracking->chekCountryOfferByIp($liOfferId);
        if (!$lbIsValidCountryByIp) {
            return $this->redirect($this->generateUrl('WebWebBundle_defaultLogoutErrorIp'));
        }
        
        if (!$loTracking->readCookies($poRequest)) {
            trigger_error('LeadTag cookies inexistant');
            return $loResponse;
        }

        // ==== Prise en compte du tracking ====
        $loTracking->handleSaleTag($lsTransaction, $lsAmount);

        return $loResponse;
    } // saleAction
}
