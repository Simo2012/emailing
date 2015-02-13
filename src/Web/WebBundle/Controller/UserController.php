<?php

namespace Web\WebBundle\Controller;

use Web\WebBundle\Form\UserTypeBIC;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Contrôleur home : page d'accueil derrière le firewall
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
    }

// profileAction

    /**
     * Page particular
     *
     * @Template()
     */
    public function particularsAction() {
        return array();
    }

// particularAction

    /**
     * Page rib
     *
     * @Template()
     */
    public function ribAction($piUserId) {
        $loManager = $this->getDoctrine()->getManager();

        $loUser = $loManager->getRepository('WebWebBundle:User')->find($piUserId);

        $loForm = $this->createForm(
                new UserTypeBIC($this->get('natexo_tool.filter.encrypt'),
                        $this->get('natexo_tool.filter.decrypt')), $loUser
        );
        // ==== Traitement de la saisie ====
        $loRequest = $this->getRequest();
        if ($loRequest->isMethod('POST')) {
            $loForm->bind($loRequest);
            try {
                $loUser->setDateUpdate(new \DateTime('now'));
                $loManager->flush();
                var_dump('ok');
                exit();
            } catch (DBALException $poException) {
                var_dump($poException);
            }
        }

        return array(
            'form' => $loForm->createView(),
            'UserId' => $loUser->getId()
        );
    }

// ribAction

    /**
     * Page cagnotte
     *
     * @Template()
     */
    public function potAction() {
        return array();
    }

// potAction

    /**
     * Page encaisser la cagnotte
     *
     * @Template()
     */
    public function cashInAction() {
        return array();
    }

// cashInAction
}
