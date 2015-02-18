<?php

namespace Web\WebBundle\Controller;

use Web\WebBundle\Form\User\UserBicType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Web\WebBundle\Form\User\UserDetailsType;
use Symfony\Component\Translation\Translator;
/**
 * Contrôleur user : pages relatives à l'utilisateur
 *
 * <pre>
 * Victor 07/02/2015 Création
 * </pre>
 * @author Victor
 * @version 1.0
 * @package Rubizz
 */
class UserController extends Controller
{
    /**
     * Page d'accueil
     *
     * @Template()
     */
    public function profileAction() {
        return array();
    }// profileAction

    /**
     * Page details (coordonnées)
     *
     * @Template()
     */
    public function detailsAction()
    {
        $loManager = $this->getDoctrine()->getManager();
        $loSessionUser = $this->getUser();
        // ---- Déconnexion de l'utilisateur en session ----
        $loManager->detach($loSessionUser);
        $lsFormError = '';
        $lbSucess = false;
        $lbCheckEmail = true;
        $loTranslator = $this->get('translator');
        /* Récupération du User */
        $loUser = $loManager->getRepository('WebWebBundle:User')->find($loSessionUser->getId());
        //recuperer l'ancien mot de passe avant saisie
        $lsCurentPass = $loSessionUser->getPassword();
        $loForm = $this->createForm(new UserDetailsType(), $loUser);

        // ==== Traitement de la saisie ====
        $loRequest = $this->getRequest();
        if ($loRequest->isMethod('POST')) {
            $loForm->bind($loRequest);
            if ($loForm->isValid()) {
                $laData = $loForm->getData();

                $loUserLogger = $this->container->get('web.web.manager.user_logger');
                $lsOldPass = $loUserLogger->cryptPass($loUser, $laData->getOldPassword());
                // on compare l'ancien pass avec celui saisi dans le form
                if ($lsOldPass == $lsCurentPass) {
                    try {
                        $loUser->setDateUpdate(new \DateTime('now'));
                        //définit si il faut une verification de l'email dans le modele
                        if ($loUser->getEmail() == $loSessionUser->getEmail()) {
                            $lbCheckEmail = false;
                        }
                        $loUserLogger->registerUser($loUser, $lbCheckEmail);

                        $lbSucess = true;
                    } catch (\Exception $poException) {
                        $lsFormError = $poException->getMessage();
                    }
                } else {
                    $lsFormError = $loTranslator->trans('web.web.user.details.connection.oldPassError');
                }
                //---- Modification de l'utilisateur en session ----
                $loSessionUser->setEmail($loUser->getEmail());
            } else {
                $loManager->refresh($loUser);
                $lsFormError = $loTranslator->trans('web.web.user.details.connection.passRepeatError');
            }
        }
        return array(
            'form' => $loForm->createView(),
            'errors' => $lsFormError,
            'success' => $lbSucess
        );
    }

// detailsAction

    /**
     * Page rib
     *
     * @Template()
     */
    public function ribAction() {
        $loManager = $this->getDoctrine()->getManager();
        $loUser = $this->getUser();
        $loForm = $this->createForm(
                new UserBicType($this->get('natexo_tool.filter.encrypt'),
                        $this->get('natexo_tool.filter.decrypt')), $loUser
        );
        // ==== Traitement de la saisie ====
        $loRequest = $this->getRequest();
        if ($loRequest->isMethod('POST')) {
            $loForm->bind($loRequest);
            try {
                $loUser->setDateUpdate(new \DateTime('now'));
                $loManager->flush();
            } catch (DBALException $poException) {
                var_dump($poException);
            }
        }
        return array(
            'form' => $loForm->createView(),
            'UserId' => $loUser->getId()
        );
    }// ribAction

    /**
     * Page cagnotte
     *
     * @Template()
     */
    public function potAction() {
        return array();
    }// potAction
    
    /**
     * Page encaisser la cagnotte
     * 
     * @Template()
     */
    public function cashInAction() {
        // ==== recuperation du user courant ====
        $loUser = $this->getUser();

        return array(
            'user'  => $loUser,
            'invoiceRequests' => array()
        );
    }
}
