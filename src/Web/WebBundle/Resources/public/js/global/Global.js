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
        // ==== Menu de profil ====
        // ---- Desktop ----
        $(document).on('mouseenter', '#ul_RBZ_desktop_profile_menu > li', function(event) {
            $(this).children('ul').slideDown("normal");
            $('#span_RBZ_down').html('&nbsp; &#708;');
            return false;
        });
        $(document).on('mouseleave', '#ul_RBZ_desktop_profile_menu > li', function(event) {
            $(this).children('ul').slideUp(100);
            $('#span_RBZ_down').html('&nbsp; &#709;');
            return false;
        });
        // ---- Tablet/Smartphone ----
        $(document).on('click', '#ul_RBZ_mobile_profile_menu > li', function(event) {
            if (!$(this).parent().hasClass('RBZ_opened')) {
                $(this).children('ul').slideDown("normal");
                $(this).find('span.RBZ_down').html('&nbsp; &#708;');
            } else {
                $(this).children('ul').slideUp(100);
                $(this).find('span.RBZ_down').html('&nbsp; &#709;');
            }
            $(this).parent().toggleClass('RBZ_opened');
            // ---- Fermeture du menu de navigation si ouvert ----
            if ($(this).parent().hasClass('RBZ_opened')) {
                if ($('#div_RBZ_menuMobile').hasClass('RBZ_opened')) {
                    $('#a_RBZ_linkMenuMobile').click();
                }
            }

            return false;
        });

        // ==== Menu de navigation (Tablet/Smartphone) ====
        $(document).on('click', '#a_RBZ_linkMenuMobile', function(event) {
            //$('#div_RBZ_menuMobile').toggleClass('RBZ_hide');
            if (!$('#div_RBZ_menuMobile').hasClass('RBZ_opened')) {
                $('#div_RBZ_menuMobile').children('ul').slideDown("normal");
            } else {
                $('#div_RBZ_menuMobile').children('ul').slideUp(100);
            }
            $('#div_RBZ_menuMobile').toggleClass('RBZ_opened');
            // ---- Fermeture du menu de profil si ouvert ----
            if ($('#div_RBZ_menuMobile').hasClass('RBZ_opened')) {
                if ($('#ul_RBZ_mobile_profile_menu').hasClass('RBZ_opened')) {
                    $('#ul_RBZ_mobile_profile_menu > li').click();
                }
            }

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
