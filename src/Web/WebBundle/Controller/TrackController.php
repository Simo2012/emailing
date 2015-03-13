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
     * Action de test
     *
     * @Template()
     */
    public function tagAction()
    {
        return array();
    } // tagAction
}
