<?php

namespace Web\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
class ContactController extends Controller {

    /**
     * Page d'accueil
     *
     * @Template()
     */
    public function indexAction() {

        $laContacts = array(
            array(
                'id'           => 1,
                'subscription' => '01.01.2015',
                'email'        => 'janvier@2015.com',
                'lastname'     => 'Demilkinze',
                'firstname'    => 'Janvier',
                'commissions'  => '1,00€',
                'status'       => 'subscribed'
            ),
            array(
                'id'           => 2,
                'subscription' => '01.02.2015',
                'email'        => 'fevrier@2015.com',
                'lastname'     => 'Demilkinze',
                'firstname'    => 'Février',
                'commissions'  => '2,00€',
                'status'       => 'unsubscribed'
            ),
            array(
                'id'           => 3,
                'subscription' => '01.03.2015',
                'email'        => 'mars@2015.com',
                'lastname'     => 'Demilkinze',
                'firstname'    => 'Mars',
                'commissions'  => '3,00€',
                'status'       => 'subscribed'
            ),
        );

        return array(
            'contacts' => $laContacts
        );
    } // indexAction
}
