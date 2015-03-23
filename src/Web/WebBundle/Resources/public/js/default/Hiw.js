/**
 * Classe de gestion globale
 *
 * <pre>
 * Victor 08/02/15 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package default
 */
function Hiw()
{
    // ==== Constructeur ====
} // Global
Hiw.prototype = {
    /**
     * Traitements lancés en fin de chargement de la page
     */
    ready: function()
    {
    }, // ready

    /**
     * Token de fin
     */
    _endPrototype: null
}; // Global.prototype

//==== Définition de l'objet Global goGlobal ====
var goHiw = new Hiw();
goHiw.ready();