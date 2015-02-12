<?php

namespace Web\WebBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Recuperer Rib d'un user
 *
 * <pre>
 * Mohammed 06/02/2014 Création
 * </pre>
 * @author Mohammed
 * @version 1.0
 * @package Web
 */
class UserRibTransformer implements DataTransformerInterface {

  
    private $crypt;
    private $decrypt;
    
    public function __construct($crypt,$decrypt) {
        $this->crypt = $crypt;
        $this->decrypt = $decrypt;
    }
     /**
     * (non-PHPdoc)
      * @see \Symfony\Component\Form\DataTransformerInterface::Transform()
     *
     */
    
    public function transform($psValue) {
        if($psValue->getBic()!=null)
        {    
            $lobicDecrypt = $this->decrypt->filter($psValue->getBic());
            $psValue->setBic($lobicDecrypt);
        }
        return $psValue;
        
    }// transform

     /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\DataTransformerInterface::reverseTransform()
     */
    public function reverseTransform($psValue)
    {
        if($psValue->getBic()!=null)
        {
            $locrypt = $this->crypt->filter($psValue->getBic());
            $psValue->setBic($locrypt);
        
        }
        return $psValue;
        
    } // reverseTransform
}
