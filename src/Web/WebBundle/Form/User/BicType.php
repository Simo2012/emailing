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
class BicType extends AbstractType {

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $poBuilder, array $paOptions) {
        // ==== Les champs de RIB ====
        $laParams = array(
            'label' => 'rubizz.form.bankName',
            'attr' => array(
                'caption' => 'rubizz.form.bankName',
                'class'   => 'RBZ_large',
            ),
        );
        $poBuilder->add('bankName', 'text', $laParams);

        $laParams = array(
            'label' => 'rubizz.form.bankCode',
            'attr' => array(
                'caption' => 'rubizz.form.bankCode',
                'class'   => 'RBZ_large',
            ),
        );
        $poBuilder->add('bankCode', 'text', $laParams);

        $laParams = array(
            'label' => 'rubizz.form.agencyCode',
            'attr' => array(
                'caption' => 'rubizz.form.agencyCode',
                'class'   => 'RBZ_large',
            ),
        );
        $poBuilder->add('agencyCode', 'text', $laParams);

          $laParams = array(
            'label' => 'rubizz.form.AccountNumber',
            'attr' => array(
                'caption' => 'rubizz.form.AccountNumber',
                'class'   => 'RBZ_large',
            ),
        );
        $poBuilder->add('AccountNumber', 'text', $laParams);

        $laParams = array(
            'label' => 'rubizz.form.Key',
            'attr' => array(
                'caption' => 'rubizz.form.Key',
            ),
        );
        $poBuilder->add('Key', 'text', $laParams);
    } // buildForm

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName() {
        return 'WebWebBicType';
    }
}
