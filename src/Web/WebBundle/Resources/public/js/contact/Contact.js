/**
 * Classe de gestion des pages du ContactController
 *
 * <pre>
 * Julien 13/02/15 Création
 * </pre>
 * @author Julien
 * @version 1.0
 * @package Rubizz
 */
function Contact()
{
    // ==== Constructeur ====
} // Default
Contact.prototype = {
    /**
     * Traitements lancés en fin de chargement de la page
     */
    ready: function () {
        /* ==== Gestion du menu "pin" ==== */
        $(document).on('click', 'div[id^=div_RBZ_pin_]', function() {
            var arraySelector = $(this).attr('id').split('_');
            var id = arraySelector[arraySelector.length - 1];
            /* ---- Fermeture du menu actuellement ouvert si différent de celui du "pin" cliqué ---- */
            $("div[id^=div_RBZ_menu_]").each(function() {
                if ($(this).attr('id') != "div_RBZ_menu_" + id) {
                    if ($(this).hasClass('RBZ_displayed')) {
                        $(this).removeClass('RBZ_displayed');
                        $(this).hide();
                    }
                }
            });
            /* ---- Ouverture et fermeture du menu correspondant au "pin" cliqué ---- */
            if ($("#div_RBZ_menu_" + id).hasClass('RBZ_displayed')) {
                $("#div_RBZ_menu_" + id).removeClass('RBZ_displayed');
                $("#div_RBZ_menu_" + id).hide();
            } else {
                $("#div_RBZ_menu_" + id).addClass('RBZ_displayed');
                $("#div_RBZ_menu_" + id).show();
            }
        });
    } // ready
}; // Contact.prototype

//==== Définition de l'objet Contact goContact ====
var goContact = new Contact();
