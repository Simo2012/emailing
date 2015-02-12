<?php

namespace Web\WebBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FlipType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'checked_label'    => 'web.web.form.flip.yes',
                'unchecked_label'  => 'web.web.form.flip.no',
            )
        );
    }

    public function getParent()
    {
        return 'checkbox';
    }

    public function getName()
    {
        return 'flip';
    }
}
