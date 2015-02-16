<?php

namespace Web\WebBundle\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Formulaire rib user
 *
 * <pre>
 * Mohammed 12/02/2015 Création
 * </pre>
 * @author Mohammed
 * @version 1.0
 * @package Web
 */
class UserDetailsType extends AbstractType {

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $poBuilder, array $paOptions) {
        // ==== Les champs de Rib  ====
        //var_dump($paOptions);
        //exit();
        $laParams = array(
            'label' => 'web.web.security.firstname',
            'attr'  => array(
                'caption' => 'web.web.security.firstname',
                'autocomplete'  => false
            )
        );
        $poBuilder->add('firstname', 'text', $laParams);
        $laParams = array(
            'label' => 'web.web.security.lastname',
            'attr'  => array(
                'caption' => 'web.web.security.lastname',
                'autocomplete'  => false
            )
        );
        
        $laParams = array(
    'label'     => 'web.web.form.sample_text',
    'attr'      => array(
        'checked_label'    => 'web.web.form.flip.yes',
        'unchecked_label'  => 'web.web.form.flip.no',
    ),
    'required' => false,
);
$poBuilder->add('optin_newsletter', 'flip', $laParams);

        
        
        
        $poBuilder->add('lastname', 'text', $laParams);
        $laParams = array(
            'label' => 'web.web.security.email',
            'attr'  => array(
                'caption' => 'web.web.security.email',
                'autocomplete'  => false
            )
        );
        $poBuilder->add('email', 'email', $laParams);
        $laParams = array(
            'label' => 'web.web.security.password',
            'attr'  => array(
                'caption' => 'web.web.security.password',
                'autocomplete'  => false
            )
        );
        $poBuilder->add('password', 'password', $laParams);
         
    }// buildForm

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $poResolver) {
        $poResolver->setDefaults(
            array(
                'data_class'      => 'Web\WebBundle\Entity\User',
                'csrf_protection' => true,
                'csrf_field_name' => '_tokenRBZ',
            )
        );
    }// setDefaultOptions

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName() {
        return 'UserDetails';
    }
}
