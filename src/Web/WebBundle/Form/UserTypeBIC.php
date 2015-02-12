<?php

namespace Web\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Web\WebBundle\Form\Transformer\UserRibTransformer;
use Web\WebBundle\Form\BICType;
use Web\WebBundle\Controller\UserController;
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
class UserTypeBIC extends AbstractType {
    private $crypt;
    private $decrypt;
    /**
     * Constructeur
     *
     * @param EntityManager $poEntityManager
     */
    public function __construct($crypt, $decrypt)
    {
        $this->crypt = $crypt;
        $this->decrypt = $decrypt;
    } // __construct
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $poBuilder, array $paOptions) {
        
        $loUserRibTransformer = new UserRibTransformer($this->crypt,$this->decrypt);
        $poBuilder->addModelTransformer($loUserRibTransformer);
        $poBuilder->add('bic', 'BicRib');
  
    }// buildForm

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $poResolver) {
        $poResolver->setDefaults(
                array(
                    'data_class' => 'Web\WebBundle\Entity\User',
                )
        );
    }// setDefaultOptions

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName() {
        return 'userbic';
    }
}
