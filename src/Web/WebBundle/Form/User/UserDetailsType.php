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
class UserDetailsType extends AbstractType
{

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $poBuilder, array $paOptions)
    {
        // ==== Les champs de Rib  ====
        $laParams = array(
            'label' => 'web.web.security.firstname',
            'attr' => array(
                'caption' => 'web.web.security.firstname',
            )
        );
        $poBuilder->add('firstname', 'text', $laParams);

        $laParams = array(
            'label' => 'web.web.security.lastname',
            'attr' => array(
                'caption' => 'web.web.security.lastname',
            )
        );
        $poBuilder->add('lastname', 'text', $laParams);

        $laParams = array(
            'label' => 'web.web.user.details.newsletter.label',
            'attr' => array(
                'checked_label' => 'web.web.form.flip.yes',
                'unchecked_label' => 'web.web.form.flip.no',
            ),
            'required' => false,
        );
        $poBuilder->add('optin_newsletter', 'flip', $laParams);

        $laParams = array(
            'label' => 'web.web.security.email',
            'attr' => array(
                'caption' => 'web.web.security.email',
            )
        );
        $poBuilder->add('email', 'email', $laParams);
        
        $laParams = array(
            'label' => 'web.web.user.details.connection.oldPass',
            'attr' => array(
                'caption' => 'web.web.user.details.connection.oldPass',
            ),
            //'mapped' => false
        );
        $poBuilder->add('oldPassword', 'password', $laParams);

        $poBuilder->add('password', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'web.web.user.details.connection.passRepeatError',
            'options' => array('required' => true),
            'first_options' => array('label' => 'web.web.user.details.connection.newPass'),
            'second_options' => array('label' => 'web.web.user.details.connection.passRepeat'),
        ));
    }

// buildForm

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $poResolver)
    {
        $poResolver->setDefaults(
                array(
                    'data_class' => 'Web\WebBundle\Entity\User',
                    'csrf_protection' => true,
                    'csrf_field_name' => '_tokenRBZ',
                )
        );
    }

// setDefaultOptions

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'UserDetails';
    }

}
