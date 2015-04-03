<?php
namespace Web\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
        // ==== Initialisation ====
        $loSession = $poRequest->getSession();
        $loCookies = $poRequest->cookies;
        $loDecrypt = $this->get('natexo_tool.filter.decrypt');
        $loManager = $this->getDoctrine()->getManager();

        // ==== Déjà loggé ====
        $lbRegistered = $loSession->get('hasRegistered');
        if (!empty($lbRegistered)) {
            return $this->redirect($this->generateUrl('WebWebBundle_offerIndex'));
        }
        // ==== Se souvenir de moi (auto login) ====
        if ($loCookies->has('RBZ_remember_me')) {
            $lsEncryptedCookie = $loCookies->get('RBZ_remember_me');
            $lsDecryptedCookie = $loDecrypt->filter($lsEncryptedCookie);
            $liUserId = $lsDecryptedCookie['user_id'];
            $loUser = $loManager->getRepository('WebWebBundle:User')->find($liUserId);
            $loUserLogger = $this->container->get('web.web.model.user.user_logger');
            $loUserLogger->setUserInSession($loUser);
            // ---- Gestion de la redirection (index/page d'ajout des contacts) ----
            if ($loUser->getNbContacts() == 0) {
                $lsUrl = $this->generateUrl('WebWebBundle_contactAdd', array(), true);
            } else {
                $lsUrl = $this->generateUrl('WebWebBundle_offerIndex', array(), true);
            }
            return $this->redirect($lsUrl);
        }

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
     * Page à propos
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
     * Popup formulaire de contact
     *
     * @Template()
     */
    public function contactAction(Request $poRequest)
    {
        // ==== Initialisation ====
        $loForm = $this->createForm('WebWebContactType');
        $loTranslator = $this->get('translator');
        // ---- Permet d'envoyer l'email à contact@rubizz.fr ou contact@rubizz.us ----
        $lsLocaleFull = $this->getRequest()->getLocale();
        $lsLocale = strtolower(substr($lsLocaleFull, -2, 2));
        if ($poRequest->isMethod('POST')) {
            $loForm->handleRequest($poRequest);
            $laData = $loForm->getData();
            // ---- Verification champ vides ----
            foreach ($laData as $lsData) {
                if (empty($lsData)) {
                    return new Response($loTranslator->trans('web.web.security.empty_fields'));
                }
            }
            if ($loForm->isValid()) {
                // ---- Préparation du texte html ----
                $lsHtml  = "Nom : " . $laData['lastname'] . '<br />';
                $lsHtml .= "Prénom : " . $laData['firstname'] . '<br />';
                $lsHtml .= "Email : " . $laData['email'] . '<br />';
                $lsHtml .= "Sujet : " . $laData['subject'] . '<br />';
                $lsHtml .= "Message :  <br />" . $laData['message'];
                try {
                    // ---- envoi de l'email ----
                    $loEmail = \Swift_Message::newInstance()
                            ->setSubject("Nouveau message reçu depuis le formulaire de contact Rubizz")
                            ->setFrom(array('admin@natexo.com' => 'Natexo admin'))
                            ->setTo("contact@rubizz.{$lsLocale}")
                            ->setBody($lsHtml, 'text/html');
                    $this->container->get('mailer')->send($loEmail);
                } catch (\Exception $lsError) {
                    return new Response($lsError->getMessage());
                }
                return new Response('OK');
            }
        }

        return array(
            'form' => $loForm->createView()
        );
    } // contactAction

    /**
     * Page Mentions légales
     *
     * @Template()
     */
    public function legalAction()
    {
        return array();
    } // legalAction

    /**
     * Page conditions générales
     *
     * @Template()
     */
    public function termsAction()
    {
        return array();
    } // termsAction
    
    /**
     * Page jobs
     *
     * @Template()
     */
    public function jobsAction()
    {
        return array();
    } // jobsAction
    
    /**
     * Page de désabonnement
     *
     * @Template()
     */
    public function unsubscribeAction(Request $poRequest)
    {
        // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();
        $loContact = null;
        // ==== Récupération de l'email ====
        $lsEmail = $poRequest->query->get('email');
        // ---- Boolean permettant de savoir si il y a eu soumission dans le twig 
        $lbIsConfirmed = false;
        
        if ($poRequest->isMethod('POST')) {
            $lbIsConfirmed = true;
            $lsEmail = $poRequest->request->get('email');
            if (!empty($lsEmail)) {
                $loContact  = $loManager->getRepository('WebWebBundle:Contact')->findOneByEmail($lsEmail);
            }
            if (!empty($loContact)) {
                $loContact->setDateUpdate(new \DateTime('now'));
                $loContact->setDirectUnsubscribe(true);
                $loManager->flush();
            }
        }
        return array(
            'email'   => $lsEmail,
            'confirm' => $lbIsConfirmed
        );
    } // unsubscribeAction
    
    /**
     * Page Erreur ip (geoIp)
     *
     * @Template()
     */
    public function errorIpAction()
    {
        return array();
    } // errorIpAction
}
