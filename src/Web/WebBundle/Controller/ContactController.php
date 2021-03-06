<?php

namespace Web\WebBundle\Controller;

use Natexo\AdminBundle\Model\Paginator;
use Natexo\ToolBundle\Model\Filter\ApiEncryptFilter;
use Web\WebBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Web\WebBundle\Model\Contact\Counter;
use Web\WebBundle\Model\Contact\Gmail;
use Web\WebBundle\Model\Contact\Outlook;
use Web\WebBundle\Model\Contact\YahooModel;

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

    /**
     * Ajout de contact
     */
    public function addAction(Request $poRequest)
    {
        // ==== Initialisation ====
        $loUser         = $this->getUser();
        $lbRegistration = ($loUser->getNbContacts() == 0) ? true : false;
        $loEncrypter    = $this->get('natexo_tool.filter.encrypt');

        $loGmailHelper = $this->get('web.web.model.contact.gmail');
        $loOutlookHelper = $this->get('web.web.model.contact.outlook');
        $loYahooHelper = $this->get('web.web.model.contact.yahoo');
        $laRetour = $loYahooHelper->get_request_token(false, false, true);
        if (empty($laRetour)) {
            $laResponse = array('xoauth_request_auth_url' => '', 'oauth_token' => '', 'oauth_token_secret' => '');
        } else {
            $laResponse = $laRetour[3];
        }
        $lsUrl = urldecode($laResponse['xoauth_request_auth_url']);

        // ==== On recupère la vue ====
        $loResponse = $this->render('WebWebBundle:Contact:add.html.twig', array(
            'registration' => $lbRegistration,
            'urlGoogle'     => $loGmailHelper->generateUrl(),
            'urlOutlook'    => $loOutlookHelper->generateUrl(),
            'urlYahoo'      => $lsUrl
        ));

        // ==== Cookie - Se souvenir de moi ====
        $lsRememberMe = $this->get('session')->get('remember_me');
        if (!empty($lsRememberMe)) {
            $loExpirationDate = new \DateTime('now');
            $loExpirationDate->add(new \DateInterval('P6M'));
            $loCookie = new Cookie(
                'RBZ_remember_me',
                $loEncrypter->filter(array('user_id' => $lsRememberMe)),
                $loExpirationDate
            );
            $loResponse->headers->setCookie($loCookie);
        }

        // ==== Mise en place du cookie contact ====
        $laData = array(
            'yahoo_request_token' => $laResponse['oauth_token'],
            'yahoo_request_token_secret' => $laResponse['oauth_token_secret'],
            'yahoo_oauth_verifier' => $laResponse['oauth_token'],
            'userId' => $loUser->getId()
        );
        $loExpirationDate = new \DateTime();
        $loExpirationDate->add(new \DateInterval('PT1H'));
        $loCookie = new Cookie('RBZ_contact', $loEncrypter->filter($laData), $loExpirationDate);
        $loResponse->headers->setCookie($loCookie);

        return $loResponse;
    } // addAction

    public function getGoogleAuthAction(Request $poRequest)
    {
        $lsCode = $poRequest->get('code');
        if (!empty($lsCode)) {
            $loGmailHelper = $this->get('web.web.model.contact.gmail');
            $loGmailHelper->setToken($lsCode);
            $loGmailHelper->readContacts();
            $loGmailHelper->saveContacts();
        }
        return $this->render('WebWebBundle:Contact:popupProvider.html.twig');
    }

    public function getOutlookAuthAction(Request $poRequest)
    {
        $lsCode = $poRequest->get('code');
        if (!empty($lsCode)) {
            $loOutlookHelper = $this->get('web.web.model.contact.outlook');
            $loOutlookHelper->readContacts();
            $loOutlookHelper->saveContacts();
        }
        return $this->render('WebWebBundle:Contact:popupProvider.html.twig');
    }

    public function getYahooAuthAction(Request $poRequest)
    {
        $lsAuthToken = $poRequest->get('oauth_token');
        if (!empty($lsAuthToken)) {
            $loYahooHelper = $this->get('web.web.model.contact.yahoo');
            $loYahooHelper->readContacts();
            $loYahooHelper->saveContacts();
        }
        return $this->render('WebWebBundle:Contact:popupProvider.html.twig');
    }

    /**
     * Popup d'ajout de contact
     *
     * @Template()
     */
    public function addPopupAction(Request $poRequest)
    {
        // ==== Initialisation ====
        $loForm = $this->createForm('WebWebContactAddType');

        $loForm->handleRequest($poRequest);

        if ($loForm->isValid()) {
            $laData = $loForm->getData();
            $loImporter = $this->container->get('web.web.model.contact.email');
            $loImporter->addContactsFromEmails($laData['contacts'], $this->getUser());
            return new Response('OK');
        } else {}

        return array(
            'form' => $loForm->createView()
        );
    } // addPopupAction

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
