<?php
namespace Web\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        return array();
    } // indexAction
}
