<?php
namespace Web\WebBundle\Model\Response;

use Symfony\Component\HttpFoundation\Response;

/**
 * Renvoie une réponse contenant une image vide
 *
 * <pre>
 * Philippe 18/03/2015 Création
 * </pre>
 * @author Philippe
 * @version 1.0
 * @package Rubizz
 */
class EmptyImgResponse
{
    /**
     * Renvoie une réponse contenant une image vide
     *
     * @return Response
     */
    public function get()
    {
        $loImg = imagecreatetruecolor(1, 1);
        $loColor = imagecolorallocate($loImg, 0, 0, 0);
        imagefill($loImg, 0, 0, $loColor);
        imagecolortransparent($loImg, $loColor);
        ob_start();
        imagegif($loImg);
        $lsContent =  ob_get_contents();
        ob_end_clean();
        $loResponse = new Response($lsContent, 200, array('content-type' => 'image/gif'));
        return $loResponse;
    } // get
}
