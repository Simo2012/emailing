/**
 * Classe de gestion globale
 *
 * <pre>
 * Victor 08/02/15 Création
 * </pre>
 * @author Victor
 * @version 1.0
 * @package Global
 */
function Global()
{
    // ==== Constructeur ====
} // Global
Global.prototype = {
    /**
     * Traitements lancés en fin de chargement de la page
     */
    ready : function()
    {
        $(document).on('mouseenter mouseleave', '#ul_RBZ_menuProfile > li', function(event) {
            $(this).children('ul').toggle();
            return false;
        });

        $(document).on('click', '#a_RBZ_linkMenuMobile', function(event) {
            $('#div_RBZ_menuMobile').toggleClass('RBZ_hide');
            return false;
        });
    }, // ready

    /**
     * Token de fin
     */
    _endPrototype : null
}; // Global.prototype

//==== Définition de l'objet Global goGlobal ====
var goGlobal = new Global();
