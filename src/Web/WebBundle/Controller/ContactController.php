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
     * @param Request $poRequest
     * @return array
     */
    public function indexAction(Request $poRequest)
    {
        // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();
        $loRequest = $this->container->get('request_stack')->getCurrentRequest();
        $laFilters = $loRequest->get('filters');
        $liPage    = $loRequest->get('page');

        // ==== Formulaire ====
        $loForm = $this->createForm('WebWebContactSearchType');

        // ==== Lecture des données ====
        $laFilters = Paginator::handleSearch($loForm, $poRequest, $liPage, $liNbItems);
        $loBuilder = $loManager->getRepository('WebWebBundle:Contact')->getByUser($this->getUser(), $laFilters);
        $liNbItems = 10;
        $loPaginator = new Paginator($loBuilder);
        $loPaginator->setPage($liPage);
        $loPaginator->setNbItemsPerPage($liNbItems);
        $loPaginator->setUrl($this->generateUrl('WebWebBundle_contactIndex'));

        // ==== Lecture du nombre de contacts ====
        $laContactsNumber = $loManager->getRepository('WebWebBundle:Contact')->countByUser($this->getUser());

        return array(
            'contacts' => $loPaginator,
            'number'   => $laContactsNumber[0],
            'form'     => $loForm->createView(),
            'page'     => $liPage
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

    /**
     * Abonne un contact
     *
     * @param $piContactId
     * @return RedirectResponse
     */
    public function subscribeAction($piContactId)
    {
        // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();
        $loContact = $loManager->getRepository('WebWebBundle:Contact')->findOneById($piContactId);
        $loRequest = $this->container->get('request_stack')->getCurrentRequest();
        $liPage    = $loRequest->get('page');

        // ==== Changement de statut ====
        if (!$loContact->getSubscriber() && !$loContact->getDirectUnsubscribe()) {
            $loContact->setSubscriber(true);
            $loManager->flush($loContact);
        }

        return $this->redirect(
            $this->generateUrl('WebWebBundle_contactIndex', array('page' => $liPage))
        );
    } // subscribeAction

    /**
     * Désabonne un contact
     *
     * @param $piContactId
     * @return RedirectResponse
     */
    public function unsubscribeAction($piContactId)
    {
        // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();
        $loContact = $loManager->getRepository('WebWebBundle:Contact')->findOneById($piContactId);
        $loRequest = $this->container->get('request_stack')->getCurrentRequest();
        $liPage    = $loRequest->get('page');

        // ==== Changement de statut ====
        if ($loContact->getSubscriber()) {
            $loContact->setSubscriber(false);
            $loManager->flush($loContact);
        }

        return $this->redirect(
            $this->generateUrl('WebWebBundle_contactIndex', array('page' => $liPage))
        );
    } // unsubscribeAction
}
