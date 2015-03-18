<?php
namespace Web\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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
    public function openAction()
    {
        $loResponse = $this->get('web.web.response.emptyImg')->get();
        return $loResponse;
    } // openAction

    /**
     * Tag de clic
     */
    public function clickAction()
    {
        $loResponse = $this->get('web.web.response.emptyImg')->get();
        return $loResponse;
    } // clickAction

    /**
     * Tag de lead
     */
    public function leadAction()
    {
        $loResponse = $this->get('web.web.response.emptyImg')->get();
        return $loResponse;
    } // leadAction

    /**
     * Tag d'achat
     */
    public function saleAction()
    {
        $loResponse = $this->get('web.web.response.emptyImg')->get();
        return $loResponse;
    } // saleAction
}
