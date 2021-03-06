<?php
namespace Web\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Web\WebBundle\Entity\User;

/**
 * Contrôleur Security : login / logout
 *
 * <pre>
 * Julien 10/02/2015 Création
 * </pre>
 * @author Julien
 * @version 1.0
 * @package Rubizz
 */
class SecurityController extends Controller
{
    /**
     * Login d'un utilisateur
     *
     * @Template()
     * @param Request $poRequest
     * @return array
     */
    public function loginAction(Request $poRequest)
    {
        // ==== Initialisation ====
        $loUser = new User();
        $loForm = $this->createForm('WebWebLoginType', $loUser);

        if ($poRequest->isMethod('POST')) {
            $loForm->handleRequest($poRequest);
            if ($loForm->isValid()) {
                try {
                    // ---- Mise en session de l'utilisateur ----
                    $loUserLogger = $this->container->get('web.web.model.user.user_logger');
                    $loLoggedUser = $loUserLogger->logUser($loUser);
                    // ---- Mise en place d'un cookie "remember me" ----
                    $laData = $poRequest->get('WebWebLoginType');
                    if (!empty($laData['remember_me'])) {
                        $loSession = $poRequest->getSession();
                        $loSession->set('remember_me', $loLoggedUser->getId());
                    }
                    // ---- Gestion de la redirection (index/page d'ajout des contacts) ----
                    if ($loLoggedUser->getNbContacts() == 0) {
                        $lsUrl = $this->generateUrl('WebWebBundle_contactAdd', array(), true);
                    } else {
                        $lsUrl = $this->generateUrl('WebWebBundle_offerIndex', array(), true);
                    }
                    return new Response(json_encode(array('status' => 'OK', 'url' => $lsUrl)));
                } catch(\Exception $e) {
                    return new Response(json_encode(array('status' => 'KO', 'error' => $e->getMessage())));
                }
            }
        }

        return array(
            'form'       => $loForm->createView(),
            'last_email' => $loUser->getEmail(),
        );
    } // loginAction

    /**
     * Enregistrement d'un utilisateur
     *
     * @Template()
     * @param Request $poRequest
     * @return array
     */
    public function registerAction(Request $poRequest)
    {
        // ==== Initialisation ====
        $loUser = new User();
        $loForm = $this->createForm('WebWebRegisterType', $loUser);
        if ($poRequest->isMethod('POST')) {
            $loForm->handleRequest($poRequest);
            if ($loForm->isValid()) {
                try {
                    $loUserLogger = $this->container->get('web.web.model.user.user_logger');
                    $loUserLogger->registerUser($loUser, true);
                    $lsUrl = $this->generateUrl('WebWebBundle_contactAdd', array(), true);
                    return new Response(json_encode(array('status' => 'OK', 'url' => $lsUrl)));
                } catch(\Exception $e) {
                    return new Response(json_encode(array('status' => 'KO', 'error' => $e->getMessage())));
                }
            } else {
               $laErrors = (string) $loForm->getErrors(true);
               return new Response(json_encode(array('status' => 'KO', 'error' => $laErrors)));
            }
        }

        return array(
            'form'           => $loForm->createView(),
            'last_firstname' => $loUser->getFirstname(),
            'last_lastname'  => $loUser->getLastname(),
            'last_email'     => $loUser->getEmail(),
        );

    } // registerAction
}
