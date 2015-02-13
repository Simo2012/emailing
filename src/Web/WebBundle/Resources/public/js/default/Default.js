/**
 * Classe de gestion des pages du DefaultController
 *
 * <pre>
 * Julien 12/02/15 Création
 * </pre>
 * @author Julien
 * @version 1.0
 * @package Rubizz
 */
function Default()
{
    // ==== Constructeur ====
} // Default
Default.prototype = {
    /**
     * Traitements lancés en fin de chargement de la page
     */
    ready: function()
    {
        // Ouverture du popup de login
        Default.prototype._manageLoginPopupOpening();
        // Fermeture du popup de login
        Default.prototype._manageLoginPopupClosing();
        // Ouverture du popup de register
        Default.prototype._manageRegisterPopupOpening();
        // Fermeture du popup de register
        Default.prototype._manageRegisterPopupClosing();
        // Ouverture du popup de login depuis le popup de register
        $(document).on("click", "#div_RBZ_registerPopup #div_RBZ_notice a.RBZ_login", function() {
            $("#div_RBZ_registerPopup #div_RBZ_close").click();
            $("span.RBZ_default_login a").click();
            return false;
        });
        // Ouverture du popup de login depuis le popup de register
        $(document).on("click", "#div_RBZ_loginPopup #div_RBZ_notice a.RBZ_register", function() {
            $("#div_RBZ_loginPopup #div_RBZ_close").click();
            $("span.RBZ_default_register a").click();
            return false;
        });
    }, // ready

    _manageLoginPopupOpening: function()
    {
        $(document).on("click", "span.RBZ_default_login a", function() {
            var lsUrl = $(this).attr('hrefbis');
            Default.prototype.ajaxPopupLogin(lsUrl);
            return false;
        });
    }, // _manageLoginPopupOpening

    _manageLoginPopupClosing: function()
    {
        $(document).on("click", "#div_RBZ_loginPopup #div_RBZ_close", function() {
            $("#div_RBZ_loginPopup").detach();
            $("#div_RBZ_loginShadow").hide();
            return false;
        });
    }, // _manageLoginPopupClosing

    _manageRegisterPopupOpening: function()
    {
        $(document).on("click", "span.RBZ_default_register a", function() {
            var lsUrl = $(this).attr('hrefbis');
            Default.prototype.ajaxPopupRegister(lsUrl);
            return false;
        });
    }, // _manageRegisterPopupOpening

    _manageRegisterPopupClosing: function()
    {
        $(document).on("click", "#div_RBZ_registerPopup #div_RBZ_close", function() {
            $("#div_RBZ_registerPopup").detach();
            $("#div_RBZ_registerShadow").hide();
            return false;
        });
    }, // _manageRegisterPopupClosing

    /**
     * Appel d'une popup en ajax
     */
    ajaxPopupLogin: function(psUrl, poClosure)
    {
        // ==== Définition de la popup ====
        $.get(psUrl, function(psAjax) {
            if (psAjax == 'refresh') {
                location.reload();
            }
            $("#div_RBZ_loginPopup").remove();
            // ==== Redéfinition de la taille du masque ====
            $("#div_RBZ_loginShadow").css({ width : $(document).width(), height : $(document).height() });
            $("#div_RBZ_loginShadow").show();
            $("body").append(psAjax);

            // ==== Centrage ====
            var liWidth = $("#div_RBZ_loginPopup").width(), liDocumentWidth = $(window).width(), liLeft = 0;
            if (liWidth < liDocumentWidth) liLeft = Math.round((liDocumentWidth - liWidth) / 2);
            $("#div_RBZ_loginPopup").css('left', liLeft);

            // ==== Appel de la closure de fin de chargement ====
            if (poClosure != undefined) {
                poClosure();
            }
        });
    }, // ajaxPopupLogin

    /**
     * Appel d'une popup en ajax
     */
    ajaxPopupRegister: function(psUrl, poClosure)
    {
        // ==== Définition de la popup ====
        $.get(psUrl, function(psAjax) {
            if (psAjax == 'refresh') {
                location.reload();
            }
            $("#div_RBZ_registerPopup").remove();
            // ==== Redéfinition de la taille du masque ====
            $("#div_RBZ_registerShadow").css({ width : $(document).width(), height : $(document).height() });
            $("#div_RBZ_registerShadow").show();
            $("body").append(psAjax);

            // ==== Centrage ====
            var liWidth = $("#div_RBZ_registerPopup").width(), liDocumentWidth = $(window).width(), liLeft = 0;
            if (liWidth < liDocumentWidth) liLeft = Math.round((liDocumentWidth - liWidth) / 2);
            $("#div_RBZ_registerPopup").css('left', liLeft);

            // ==== Appel de la closure de fin de chargement ====
            if (poClosure != undefined) {
                poClosure();
            }
        });
    }, // ajaxPopupRegister

    /**
     * Token de fin
     */
    _endPrototype : null
}; // Default.prototype

//==== Définition de l'objet Default goDefault ====
var goDefault = new Default();
