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
        // ==== Les champs de Rib  ====
        //var_dump($paOptions);
        //exit();
        $laParams = array(
            'label' => 'rubizz.form.bankName',
            'attr' => array(
                'caption' => 'rubizz.form.bankName',
            ),
        );
        $poBuilder->add('bankName', 'text', $laParams);
        
        $laParams = array(
            'label' => 'rubizz.form.bankCode',
            'attr' => array(
                'caption' => 'rubizz.form.bankCode',
            ),
        );
        $poBuilder->add('bankCode', 'text', $laParams);
        
        $laParams = array(
            'label' => 'rubizz.form.agencyCode',
            'attr' => array(
                'caption' => 'rubizz.form.agencyCode',
            ),
        );
        $poBuilder->add('agencyCode', 'text', $laParams);
        
          $laParams = array(
            'label' => 'rubizz.form.AccountNumber',
            'attr' => array(
                'caption' => 'rubizz.form.AccountNumber',
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
        //var_dump($paOptions);
       // exit();
         
    }// buildForm

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $poResolver) {
        $poResolver->setDefaults(
                array(
                    
                )
        );
    }// setDefaultOptions

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName() {
        return 'bictype';
    }
}
