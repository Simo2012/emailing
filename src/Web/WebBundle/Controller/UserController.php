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
class UserController extends Controller {

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
    public function detailsAction() {
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
    }// detailsAction

    /**
     * Page rib
     *
     * @Template()
     */
    public function ribAction() {
        $loManager = $this->getDoctrine()->getManager();
        $loUser = $this->getUser();
        $loForm = $this->createForm(
                new UserBicType($this->get('natexo_tool.filter.encrypt'), $this->get('natexo_tool.filter.decrypt')), $loUser
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
    public function potAction(Request $poRequest) {
        //return array();
        // ==== Lecture des données ====
        $loDate = $poRequest->get('piDate');
        $piTypeMouvement = $poRequest->get('piType');
           // ==== Initialisation ====
        $loManager = $this->getDoctrine()->getManager();
        $loUser = $this->getUser();
        $loBuilder = $loManager->getRepository('WebWebBundle:Movement')->getAllByUser($loUser);
        $liNbItems = 3;
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
    public function cashInAction() {
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
