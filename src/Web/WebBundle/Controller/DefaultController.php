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
    public function indexAction(Request $poRequest)
    {
        // ==== Déjà loggé ====
        $loSession = $poRequest->getSession();
        $lbRegistered = $loSession->get('hasRegistered');
        if (!empty($lbRegistered)) {
            return $this->redirect($this->generateUrl('WebWebBundle_offerIndex'));
        }
        //$loDecrypt = $this->get('natexo_tool.filter.decrypt'); // DEBUG
        //$laDebug = $loDecrypt->filter('!1!ulrNRlS3IceSiT8iHYsvYwBJ3csonBoOgpg,FA7c7PY='); // DEBUG
        //var_dump($laDebug); // DEBUG
        //$loEncrypt = $this->get('natexo_tool.filter.encrypt'); // DEBUG
        //$lsDebug = $loEncrypt->filter(array('bubu')); // DEBUG
        //echo "DEBUG: {$lsDebug}<br />"; // DEBUG

        return array();
    } // indexAction

    /**
     * Page comment ça marche
     *
     * @Template()
     */
    public function howItWorksAction()
    {
        return array();
    } // howItWorksAction

    /**
     * Page A porpos
     *
     * @Template()
     */
    public function aboutAction()
    {
        return array();
    } // aboutAction

    /**
     * Page FAQ
     *
     * @Template()
     */
    public function faqAction()
    {
        return array();
    } // faqAction

    /**
     * Page contact
     *
     * @Template()
     */
    public function contactAction()
    {
        return array();
    } // contactAction

    /**
     * Page jobs
     *
     * @Template()
     */
    public function jobsAction()
    {
        return array();
    } // jobsAction
}
