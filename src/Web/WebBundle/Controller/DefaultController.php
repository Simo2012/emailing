<?php
namespace Web\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Contrôleur default : page d'accueil, inscription
 *
 * <pre>
 * Philippe 05/02/2015 Création
 * </pre>
 * @author Philippe
 * @version 1.0
 * @package Rubizz
 */
class DefaultController extends Controller
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
