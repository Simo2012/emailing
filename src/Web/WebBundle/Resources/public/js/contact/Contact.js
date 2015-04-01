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
    }, // ready

    handleProvider: function () {
        $(document).on('click', '#a_RBZ_importGmail, #a_RBZ_importOutlook, #a_RBZ_importYahoo', function() {
            var popup = window.open($(this).attr('hrefbis'), '', 'height=400,width=600');
            if (window.focus) {newwindow.focus()}
            return false;
        });

        $(document).on('click', '#a_RBZ_importEmail', function() {
            goPopup.ajaxPopup($(this).attr('hrefbis'));
            return false;
        });

        $(document).on("click", "#a_RBZ_addContact", function() {
            Contact.prototype._contactId++;
            var lsProto = $("#div_RBZ_emailsContainer").attr('data-prototype').replace(/__name__/g, Contact.prototype._contactId);
            $("#div_RBZ_emailsContainer").append(lsProto);
            return false;
        });

        $(document).on("click", "#button_RBZ_contactSubmit", function() {
            $('#form_RBZ_addContact').submit();
            return false;
        });

        /**
         * Validation du formulaire dans le popup de contact
         */
        $(document).on("submit", "#form_RBZ_addContact", function() {
            var loData = new FormData($(this)[0]);
            console.log('submit');
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: loData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (psReturn) {
                    if ('OK' == psReturn) {
                        $("#div_RBZ_commonPopup").remove();
                        $("#div_RBZ_commonShadow").hide();
                        return false;
                    } else {
                        $("#div_RBZ_error").text('');
                        $("#div_RBZ_error").append(psReturn);
                    }
                }
            });
            return false;
        });
    },
        /* ==== Fermeture de la popup de bienvenue ==== */
        closePopupWelcome: function () {
            /* ---- Fermeture de la popup (welcome) ---- */
            $(document).on('click', '#img_RBZ_welcomePopupClose', function() {
                $('#div_RBZ_welcomePopupInside').remove();
                return false;
            });
    },

    _contactId: 0


}; // Contact.prototype

//==== Définition de l'objet Contact goContact ====
var goContact = new Contact();
