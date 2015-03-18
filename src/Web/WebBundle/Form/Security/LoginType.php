<?php
namespace Web\WebBundle\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LoginType extends AbstractType
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $poBuilder, array $paOptions)
    {
        $laParams = array(
            'label'    => 'web.web.security.email',
            'required' => true,
            'attr'     => array(
                'caption' => 'web.web.security.email',
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
                'caption' => 'web.web.security.password',
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
                'validation_groups' => 'login'
            )
        );
    } // setDefaultOptions

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'WebWebLoginType';
    } // getName
}
