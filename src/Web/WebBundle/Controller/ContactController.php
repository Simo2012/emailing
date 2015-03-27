<?php

namespace Web\WebBundle\Controller;

use Natexo\AdminBundle\Model\Paginator;
use Natexo\ToolBundle\Model\Filter\ApiEncryptFilter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
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

    /** ajout de contact
     *
     * @Template()
     */
    public function addAction(Request $poRequest)
    {
        $loUser = $this->getUser();
        $lbRegistration = ($loUser->getNbContacts() == 0) ? true : false;
        $loSession = $poRequest->getSession();

        $loGmailHelper = new Gmail();
        $laApi = array(
            'client_id' => '000000004C147233',
            'client_secret' => 'YKssmSJ4P4VihF0xxwJM4PcDEedJkHL1',
            'redirect_uri' => 'http://rubizz.victor.natexo.com/app_dev.php/authOutlook'
        );
        $loOutlookHelper = new Outlook($laApi);

        $lsConsumer_key = 'dj0yJmk9bkx2Q2hhVWFGaFJWJmQ9WVdrOWNqWTNRbXh6TXpBbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD1iNg--';
        $lsConsumer_secret = '77cf01c47f4cb5ee0cb080d9183bc405313d8057';
        $loYahooHelper = new YahooModel(
            $lsConsumer_key,
            $lsConsumer_secret
        );
        $lsCallbackUrl = 'http://rubizz.victor.natexo.com/app_dev.php/authYahoo';
        $retour = $loYahooHelper->get_request_token($lsConsumer_key, $lsConsumer_secret, $lsCallbackUrl, false, false, true);
        $response = $retour[3];
        $url = urldecode($response['xoauth_request_auth_url']);
        $laData = array(
            'yahoo_request_token' => $response['oauth_token'],
            'yahoo_request_token_secret' => $response['oauth_token_secret'],
            'yahoo_oauth_verifier' => $response['oauth_token']
        );

        // ==== on recupère la vue ====
        $loResponse = $this->render('WebWebBundle:Contact:add.html.twig', array(
            'registration' => $lbRegistration,
            'urlGoogle'     => $loGmailHelper->generateUrl(),
            'urlOutlook'    => $loOutlookHelper->generateUrl(),
            'urlYahoo'      => $url
        ));
        $loEncrypter = $this->get('natexo_tool.filter.encrypt');
        // ---- mise en place du cookie ----

        $loExpirationDate = new \DateTime();
        $loExpirationDate->add(new \DateInterval('PT1H'));
        $loCookie = new Cookie('RBZ', $loEncrypter->filter($laData));
        $loResponse->headers->setCookie($loCookie);
        return $loResponse;
    } // addAction

    public function getGoogleAuthAction(Request $poRequest)
    {
        $lsCode = $poRequest->get('code');
        if (!empty($lsCode)) {
            $loHelper = new Gmail();
            $loHelper->setToken($lsCode);
            $loHelper->readContacts();
        }
        return new Response('OK');
    }

    public function getOutlookAuthAction(Request $poRequest)
    {
        $lsCode = $poRequest->get('code');
        if (!empty($lsCode)) {
            $laApi = array(
                'client_id' => '000000004C147233',
                'client_secret' => 'YKssmSJ4P4VihF0xxwJM4PcDEedJkHL1',
                'redirect_uri' => 'http://rubizz.victor.natexo.com/app_dev.php/authOutlook'
            );
            $loHelper = new Outlook($laApi);
            $laContacts = $loHelper->getContacts();
        }
        return new Response('OK');
    }

    public function getYahooAuthAction(Request $poRequest)
    {
        $loSession = $poRequest->getSession();
        $lsAuthToken = $poRequest->get('oauth_token');
        if (!empty($lsAuthToken)) {
            $lsConsumer_key = 'dj0yJmk9bkx2Q2hhVWFGaFJWJmQ9WVdrOWNqWTNRbXh6TXpBbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD1iNg--';
            $lsConsumer_secret = '77cf01c47f4cb5ee0cb080d9183bc405313d8057';
            $loYahooHelper = new YahooModel($lsConsumer_key, $lsConsumer_secret);
            if ($poRequest->cookies->has('RBZ')) {
                $lsCookie = $poRequest->cookies->get('RBZ');
            }
            $loDecrypter = $this->get('natexo_tool.filter.decrypt');
            $laDecrypt = $loDecrypter->filter($lsCookie);
            $request_token          =   $laDecrypt['yahoo_request_token'];
            $request_token_secret   =   $laDecrypt['yahoo_request_token_secret'];
            $oauth_verifier         =   $poRequest->get('oauth_verifier');
            $all = $loYahooHelper->getContacts($request_token, $request_token_secret, $oauth_verifier);
            $i = 0;
            foreach ($all['contacts']['contact'] as $contacts) {
                if(count($contacts['fields']) > 1) {
                    if($contacts['fields'][1]['type'] == 'email' ||
                        $contacts['fields'][1]['type'] == 'phone') {
                        var_dump($contacts['fields'][1]['value']);
                        $i++;
                    }
                } else {
                    if($contacts['fields'][0]['type'] == 'email' ||
                        $contacts['fields'][0]['type'] == 'phone') {
                        var_dump($contacts['fields'][0]['value']);
                        $i++;
                    }
                }
            }
            var_dump("number ".$i);
        }
        return new Response('OK');
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
            $loImporter->addContactsFromEmails($laData['emails'], $this->getUser());
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
