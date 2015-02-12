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

    /**
     * Mot de passe oublié
     *
     * @Template()
     */
    public function forgotAction(Request $poRequest)
    {
        $loForm = $this->createForm(new ForgotType());
        $lbError = false;
        if ('POST' == $poRequest->getMethod()) {
            $loForm->bind($poRequest);
            if ($loForm->isValid()) {
                $laData = $loForm->getData();
                $loManager = $this->getDoctrine()->getManager();
                $loUser = $loManager->getRepository('PublisherWebBundle:Common\Publisher')->findOneBy(
                    array('login' => $laData['login'])
                );
                if (!empty($loUser)) {
                    $loEncrypt = $this->get('natexo_tool.filter.encrypt');
                    $loDate = new \DateTime();
                    $lsToken = $loEncrypt->filter(
                        array(
                            'email' => $loUser->getLogin(),
                            'date' => $loDate->format('Y-m-d H:i:s')
                        )
                    );
                    $loEmail = \Swift_Message::newInstance()
                        ->setSubject('Lost password')
                        ->setFrom(array('admin@natexo.com' => 'Interface Natexo'))
                        ->setTo($loUser->getLogin())
                        ->setBody(
                            $this->renderView(
                                'PublisherWebBundle:Security:email/forgot.html.twig',
                                array('user' => $loUser, 'token' => $lsToken)
                            ),
                            'text/html'
                        );
                    $this->get('mailer')->send($loEmail);
                }
                return $this->redirect($this->generateUrl('PublisherWebBundle_securityLogin'));
            } else {
                $lbError = true;
            }
        }

        return array (
            'form'      => $loForm->createView(),
            'error'     => $lbError,
            'languages' => $this->container->getParameter('languages')
        );
    } // forgotAction

    /**
     * Réinitialisation du mot de passe
     *
     * @Template()
     */
    public function resetAction(Request $poRequest)
    {
        // ==== Initialisation ====
        $psToken = $poRequest->get('psToken');
        $loManager = $this->getDoctrine()->getManager();
        $loDecrypt = $this->get('natexo_tool.filter.decrypt');
        $lbSucess = false;

        try {
            // ---- Try decrypt -----
            $laData = $loDecrypt->filter($psToken);
            $loDate = \DateTime::createFromFormat('Y-m-d H:i:s', $laData['date']);
            $loDate->add(new \DateInterval('P1D'));
            $loNow = new \DateTime();

            // ==== Verifie si le lien est encore valid ====
            if ($loNow > $loDate) {
                return array('invalidLink' => true);
            }
            $loUser = $loManager->getRepository('PublisherWebBundle:Common\Publisher')->findOneBy(
                array('login' => $laData['email'])
            );
            $loForm = $this->createForm(new PasswordType(), $loUser);

            $loForm->handleRequest($poRequest);
            if ($loForm->isValid()) {
                $loManager->flush();
                $lbSucess = true;
            }

            return array(
                'form'      => $loForm->createView(),
                'user'      => $loUser,
                'success'   => $lbSucess
            );
        } catch (\InvalidArgumentException $peException) {
            if ($peException->getMessage() == 'Crypted content is not correct') {
                return $this->redirect($this->generateUrl('PublisherWebBundle_securityLogin'));
            }
        }

    } // resetAction
}
