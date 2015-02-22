<?php

namespace Web\WebBundle\Controller;

use Natexo\AdminBundle\Model\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Contrôleur contact : pages relatives aux contacts de l'utilisateur
 *
 * <pre>
 * Julien 13/02/2015 Création
 * </pre>
 * @author Julien
 * @version 1.0
 * @package Rubizz
 */
class ContactController extends Controller
{

    /**
     * Page d'accueil
     *
     * @Template()
     */
    public function indexAction(Request $poRequest)
    {
        // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();

        // ==== Lecture des données ====
        $loBuilder = $loManager->getRepository('WebWebBundle:Contact')->getAll();
        $liNbItems = 10;
        Paginator::paginate($poRequest, $liPage, $liNbItems);
        $loPaginator = new Paginator($loBuilder);
        $loPaginator->setPage($liPage);
        $loPaginator->setNbItemsPerPage($liNbItems);
        $loPaginator->setUrl($this->generateUrl('WebWebBundle_contactIndex'));

        return array(
            'contacts' => $loPaginator
        );
    } // indexAction

    /** ajout de contact
     *
     * @Template()
     */
    public function addAction(Request $poRequest)
    {
        $lbRegistration = $poRequest->get('registration', false);

        return array(
            'registration' => $lbRegistration
        );
    } // addAction
}
