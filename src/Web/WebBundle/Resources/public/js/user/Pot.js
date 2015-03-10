/**
 * Classe de gestion credit debit cagnotte
 *
 * <pre>
 * Mohammed 09/03/2015 Création
 * </pre>
 * @author Mohammed
 * @version 1.0
 * @package Web
 */
function Pot()
{
    // ==== Constructeur ====
} // Pot
Pot.prototype = {
    /**
     * Traitements lancés en fin de chargement de la page
     */
    ready: function ()
    {
        $(".RBZ_user_pot_mtd").click(function () {
            console.log(($(this).attr('hrefbis')));
            goPopup.ajaxPopup($(this).attr('hrefbis'));

            return false;
        });
        $(".RBZ_user_pot_mtc").click(function () {
            console.log(($(this).attr('hrefbis')));
            goPopup.ajaxPopup($(this).attr('hrefbis'));

            return false;
        });
        // ---- 

    }, // ready
    /**
     * Token de fin
     */
    _endPrototype: null
}; // Pot.prototype

//==== Définition de l'objet global goPot ====
var goPot = new Pot();
$(function () {
    goPot.ready();
});