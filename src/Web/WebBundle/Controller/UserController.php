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
class UserController extends Controller
{
    /**
     * Page d'accueil
     *
     * @Template()
     */
    public function profileAction()
    {
        return array();
    } // profileAction

    /**
     * Page particular
     *
     * @Template()
     */
    public function particularAction()
    {
        return array();
    } // particularAction

    /**
     * Page rib
     *
     * @Template()
     */
    public function ribAction()
    {
        return array();
    } // ribAction

    /**
     * Page cagnotte
     *
     * @Template()
     */
    public function potAction()
    {
        return array();
    } // potAction

    /**
     * Page encaisser la cagnotte
     *
     * @Template()
     */
    public function cashInAction()
    {
        return array();
    } // cashInAction
}
