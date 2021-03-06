<?php
namespace Web\WebBundle\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegisterType extends AbstractType
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $poBuilder, array $paOptions)
    {
        $laParams = array(
            'label'    => 'web.web.security.firstname',
            'required' => true,
            'attr'     => array(
                'caption'      => 'web.web.security.firstname',
                'autocomplete' => false,
                'placeholder'  => 'web.web.security.firstname.placeholder',
                'class'        => 'RBZ_placeholder'
            )
        );
        $poBuilder->add('firstname', 'text', $laParams);
        $laParams = array(
            'label'    => 'web.web.security.lastname',
            'required' => true,
            'attr'     => array(
                'caption'      => 'web.web.security.lastname',
                'autocomplete' => false,
                'placeholder'  => 'web.web.security.lastname.placeholder',
                'class'        => 'RBZ_placeholder'
            )
        );
        $poBuilder->add('lastname', 'text', $laParams);
        $laParams = array(
            'label'    => 'web.web.security.email',
            'required' => true,
            'attr'     => array(
                'caption'      => 'web.web.security.email',
                'autocomplete' => false,
                'placeholder'  => 'web.web.contact.add.provider.email',
                'class'        => 'RBZ_placeholder'
            )
        );
        $poBuilder->add('email', 'email', $laParams);
        $laParams = array(
            'label'    => 'web.web.security.password',
            'required' => true,
            'attr'     => array(
                'caption'      => 'web.web.security.password',
                'autocomplete' => false,
                'placeholder'  => 'web.web.security.pass.placeholder',
                'class'        => 'RBZ_placeholder'
            )
        );
        $poBuilder->add('password', 'password', $laParams);
    } // buildForm

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $poResolver)
    {
        $poResolver->setDefaults(
            array(
                'data_class'        => 'Web\WebBundle\Entity\User',
                'validation_groups' => 'register'
            )
        );
    } // setDefaultOptions

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'WebWebRegisterType';
    } // getName
}
