<?php

namespace Web\WebBundle\Controller;

use Web\WebBundle\Form\User\UserBicType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Web\WebBundle\Form\User\UserDetailsType;
use Symfony\Component\Translation\Translator;
use Natexo\AdminBundle\Model\Paginator;

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
     * Page details (coordonnées)
     *
     * @Template()
     */
    public function detailsAction()
    {
        // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();
        $loSessionUser = $this->getUser();
        // ---- Déconnexion de l'utilisateur en session ----
        $loManager->detach($loSessionUser);
        $lsFormError = '';
        $lbSuccess = false;
        $lbCheckEmail = true;
        $loTranslator = $this->get('translator');
        $loUser = $loManager->getRepository('WebWebBundle:User')->find($loSessionUser->getId());
        // ---- Récuperer l'ancien mot de passe avant saisie ----
        $lsCurrentPass = $loSessionUser->getPassword();
        $loForm = $this->createForm('WebWebUserDetailsType', $loUser);

        // ==== Traitement de la saisie ====
        $loRequest = $this->getRequest();
        if ($loRequest->isMethod('POST')) {
            $loForm->bind($loRequest);
            if ($loForm->isValid()) {
                $loUserLogger = $this->container->get('web.web.model.user.user_logger');
                $lsNewPassword = $loUserLogger->cryptPass($loUser, $loUser->getPassword());
                // ---- Faut-il mettre à jour le mot de passe ? ----
                if ($loSessionUser->getPassword() != $lsNewPassword) {
                    $lsOldPass = $loUserLogger->cryptPass($loUser, $loUser->getOldPassword());
                    // ---- On compare l'ancien password avec celui saisi dans le form ----
                    if ($lsOldPass == $lsCurrentPass) {
                        $loUser->setPassword($lsNewPassword);
                        $lbSuccess = true;
                    } else {
                        $lsFormError = $loTranslator->trans('web.web.user.details.connection.oldPassError');
                    }
                }
                // ---- Faut-il mettre à jour l'email ? ----
                if ($loSessionUser->getEmail() != $loUser->getEmail()) {
                    try {
                        $loUserLogger->registerUser($loUser, true);
                        $lbSuccess = true;
                    } catch (\Exception $poException) {
                        $lsFormError = $poException->getMessage();
                    }
                }
                if (empty($lsFormError)) {
                    $loUser->setDateUpdate(new \DateTime('now'));
                    $loManager->flush();
                    return $this->redirect($this->generateUrl('WebWebBundle_userDetails'));
                }
            } else {
                $loManager->refresh($loUser);
                $lsFormError = $loTranslator->trans('web.web.user.details.connection.passRepeatError');
            }
        }
        return array(
            'form'    => $loForm->createView(),
            'errors'  => $lsFormError,
            'success' => $lbSuccess
        );
    } // detailsAction

    /**
     * Page rib
     *
     * @Template()
     */
    public function ribAction()
    {
        // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();
        $loUser = $this->getUser();
        $loForm = $this->createForm('WebWebUserBicType', $loUser);

        // ==== Traitement de la saisie ====
        $loRequest = $this->getRequest();
        if ($loRequest->isMethod('POST')) {
            $loForm->bind($loRequest);
            $loUser->setDateUpdate(new \DateTime('now'));
            $loManager->flush();
        }
        return array(
            'form'   => $loForm->createView(),
            'UserId' => $loUser->getId()
        );
    }// ribAction

    /**
     * Page cagnotte
     *
     * @Template()
     */
    public function potAction(Request $poRequest)
    {
        // ==== Lecture des données ====
        $loDate = $poRequest->get('piDate');
        $piTypeMouvement = $poRequest->get('piType');
           // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();
        $loUser = $this->getUser();
        $loBuilder = $loManager->getRepository('WebWebBundle:Movement')->getAllByUser($loUser);
        $liNbItems = 5;
        Paginator::paginate($poRequest, $liPage, $liNbItems);
        $loPaginator = new Paginator($loBuilder);
        $loPaginator->setPage($liPage);
        $loPaginator->setNbItemsPerPage($liNbItems);
        $loPaginator->setUrl($this->generateUrl('WebWebBundle_userPot'));
        if ($piTypeMouvement == 'credit') {
            $commissions = $loManager->getRepository('WebWebBundle:Commission')->getUserCommissions($loUser,$loDate);
            return $this->render('WebWebBundle:user:pot/credit.html.twig',array('commissions' => $commissions));
        } elseif ($piTypeMouvement == 'debit') {
            $loPaymentRequest = $loManager->getRepository('WebWebBundle:PaymentRequest')->getAllByUser($loUser,$loDate);
            return $this->render('WebWebBundle:user:pot/debit.html.twig',array('invoiceRequests' => $loPaymentRequest));
        }
        // ==== recuperation des paiements ====
        return array(
            'movements' => $loPaginator
        );
    }// potAction

    /**
     * Page encaisser la cagnotte
     *
     * @Template()
     */
    public function cashInAction()
    {
        $loManager = $this->getDoctrine()->getManager();
        // ==== recuperation du user courant ====
        $loUser = $this->getUser();

        // ==== recuperation des paiements ====
        $loPaymentRequest = $loManager->getRepository('WebWebBundle:PaymentRequest')->findBy(
            array('user' => $loUser), array('dateCreate' => 'desc'), 10, 0
        );

        return array(
            'user' => $loUser,
            'invoiceRequests' => $loPaymentRequest
        );
    }
}
