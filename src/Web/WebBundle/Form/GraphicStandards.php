<?php
namespace Web\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Formulaire
 *
 * <pre>
 * Victor 11/02/2015 Création
 * </pre>
 * @author Victor
 * @version 1.0
 * @package webWeb
 */
class GraphicStandards extends AbstractType
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $poBuilder, array $paOptions)
    {
        $laParams = array(
            'label'         => 'web.web.form.sample_text',
            'required'      => false,
        );
        $poBuilder->add('text1', 'text', $laParams);

        $laParams = array(
            'label'         => 'web.web.form.sample_text',
            'required'      => false,
        );
        $poBuilder->add('text2', 'text', $laParams);

        $laParams = array(
            'label'         => 'web.web.form.sample_text',
            'required'      => false,
        );
        $poBuilder->add('texterror', 'text', $laParams);

        $laParams = array(
            'label'     => 'web.web.form.sample_text',
            'attr'      => array(
                'checked_label'    => 'web.web.form.flip.yes',
                'unchecked_label'  => 'web.web.form.flip.no',
            ),
            'required' => false,
        );
        $poBuilder->add('flip', 'flip', $laParams);
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
        return 'graphicStandards';
    } // getName
}
