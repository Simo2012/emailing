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
        $(".RBZ_user_pot_debit").click(function () {
            var url = $(this).attr('hrefbis');
            var windowWidth = $(window).width();
            url = url + '?width=' + windowWidth;
            goPopup.ajaxPopup(url);

            return false;
        });
        $(".RBZ_user_pot_credit").click(function () {
            var url = $(this).attr('hrefbis');
            var windowWidth = $(window).width();
            url = url + '?width=' + windowWidth;
            goPopup.ajaxPopup(url);

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