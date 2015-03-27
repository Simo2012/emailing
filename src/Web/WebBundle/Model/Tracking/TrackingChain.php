<?php
namespace Web\WebBundle\Model\Tracking;

use Web\WebBundle\Model\Tracking\AbstractTracking;

/**
 * Lecture des modèles de lecture des stats
 *
 * <pre>
 * Julien 25/03/2015 Création
 * </pre>
 * @author Julien
 * @version 1.0
 * @package Web
 */
class TrackingChain
{
    /**
     * Tableau des modèles de tracking
     *
     * @var array
     */
    private $models;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->models = array();
    } // __construct

    /**
     * Ajoute un modèle à la liste
     *
     * @param AbstractTracking $poModel Le modèle
     * @param string $psAlias Son alias
     */
    public function addModel(AbstractTracking $poModel, $psAlias)
    {
        $this->models[$psAlias] = $poModel;
    } // addModel

    /**
     * Renvoie un modèle par son alias
     *
     * @param string $psAlias Alias du modèle
     * @return AbstractStat
     */
    public function getModel($psAlias)
    {
        if (array_key_exists($psAlias, $this->models)) {
            return $this->models[$psAlias];
        } else {
            return;
        }
    } // getModel

    /**
     * Renvoie la liste des modèles déclarés
     *
     * @return array
     */
    public function getList()
    {
        $laList = array_keys($this->models);
        asort($laList);
        return $laList;
    } // getList
}
