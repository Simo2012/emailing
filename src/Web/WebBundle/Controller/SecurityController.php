<?php
namespace Web\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
 * @package publisher
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
        // ==== Déjà loggé ====
        $loSession = $poRequest->getSession();
        $lbRegistered = $loSession->get('hasRegistered');
        if (!empty($lbRegistered)) {
            return $this->redirect($this->generateUrl('WebWebBundle_homeIndex'));
        }

        // ==== Initialisation ====
        $loUser = new User();
        $loForm = $this->createForm('WebWebLoginType', $loUser);

        if ($poRequest->isMethod('POST')) {
            $loForm->handleRequest($poRequest);
            if ($loForm->isValid()) {
                try {
                    $loUserLogger = $this->container->get('web.web.manager.user_login');
                    $loUserLogger->logUser($loUser);
                } catch(\Exception $e) {
                    return array(
                        'form'       => $loForm->createView(),
                        'last_email' => $loUser->getEmail(),
                        'error'      => $e->getMessage()
                    );
                }

                return $this->redirect($this->generateUrl('WebWebBundle_offerIndex'));
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
                    $loUserLogger = $this->container->get('web.web.manager.user_login');
                    $loUserLogger->registerUser($loUser);
                } catch(\Exception $e) {
                    return array(
                        'form'           => $loForm->createView(),
                        'last_firstname' => $loUser->getFirstname(),
                        'last_lastname'  => $loUser->getLastname(),
                        'last_email'     => $loUser->getEmail(),
                        'error'          => $e->getMessage()
                    );
                }

                return $this->redirect($this->generateUrl('WebWebBundle_offerIndex'));
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