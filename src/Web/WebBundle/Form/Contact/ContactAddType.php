<?php
namespace Web\WebBundle\Form\Contact;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactAddType extends AbstractType
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $poBuilder, array $paOptions)
    {
        $laParams = array(
            'type'          => 'WebWebShortContactType',
            'allow_add'     => true,
            'allow_delete'  => true,
            'delete_empty'  => true,
            'options'  => array(
                'attr'          => array(
                    'placeholder' => 'my.email__name__@example.com'
                )
            )
        );
        $poBuilder->add('contacts', 'collection', $laParams);
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
        return 'WebWebContactAddType';
    } // getName
}
