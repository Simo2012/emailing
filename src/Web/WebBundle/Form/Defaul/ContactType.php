<?php
namespace Web\WebBundle\Form\Defaul;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $poBuilder, array $paOptions)
    {
        $laParams = array(
            'label' => 'web.web.security.lastname',
            'required' => true,
            'attr' => array(
                'caption' => 'web.web.security.lastname',
                'autocomplete' => false,
                'placeholder'  => 'web.web.security.lastname.placeholder',
                'class'        => 'RBZ_placeholder'
            )
        );
        $poBuilder->add('lastname', 'text', $laParams);

        $laParams = array(
            'label' => 'web.web.security.firstname',
            'required' => true,
            'attr' => array(
                'caption' => 'web.web.security.firstname',
                'autocomplete' => false,
                'placeholder'  => 'web.web.security.lastname.placeholder',
                'class'        => 'RBZ_placeholder'
            )
        );
        $poBuilder->add('firstname', 'text', $laParams);

        $laParams = array(
            'label' => 'web.web.security.email',
            'required' => true,
            'attr' => array(
                'caption' => 'web.web.security.email',
                'autocomplete' => false,
                'placeholder'  => 'web.web.contact.add.provider.email',
                'class'        => 'RBZ_placeholder'
            )
        );

        $poBuilder->add('email', 'email', $laParams);
        
        $laParams = array(
            'label' => 'web.web.default.contact.subject',
            'required' => true,
            'attr' => array(
                'caption' => 'web.web.default.contact.subject',
                'autocomplete' => false,
                'placeholder'  => 'web.web.default.subject.placeholder',
                'class'        => 'RBZ_placeholder'
            )
        );
        $poBuilder->add('subject', 'text', $laParams);
        
        $laParams = array(
            'label' => 'web.web.default.contact.message',
            'required' => true,
            'attr' => array(
                'caption' => 'web.web.default.contact.message',
                'autocomplete' => false,
                'placeholder'  => 'web.web.default.message.placeholder',
                'class'        => 'RBZ_placeholder'
            )
        );
        $poBuilder->add('message', 'textarea', $laParams);
    } // buildForm

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $poResolver)
    {
        $poResolver->setDefaults(
            array()
        );
    } // setDefaultOptions

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'WebWebContactType';
    } // getName
}
