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
    ready: function(psLoginUrl)
    {
        // Ouverture du popup de login
        Default.prototype._manageLoginPopupOpening();
        // Soumission du popup de login
        Default.prototype._manageLoginPopupSubmitting(psLoginUrl);
        // Fermeture du popup de login
        Default.prototype._manageLoginPopupClosing();
        // Ouverture du popup de register
        Default.prototype._manageRegisterPopupOpening();
        // Soumission du popup de register
        Default.prototype._manageRegisterPopupSubmitting(psLoginUrl);
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
        $(document).on("click", "#a_RBZ_login", function() {
            var lsUrl = $(this).attr('hrefbis');
            Default.prototype.ajaxPopupLogin(lsUrl);
            return false;
        });
    }, // _manageLoginPopupOpening

    _manageLoginPopupSubmitting: function(psLoginUrl)
    {
        $(document).on("click", "#input_RBZ_login_form ", function() {
            Default.prototype.ajaxPopupLoginSubmit('form_RBZ_login_form', psLoginUrl);
            return false;
        });
    }, // _manageLoginPopupSubmitting

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
        $(document).on("click", "a.RBZ_register", function() {
            var lsUrl = $(this).attr('hrefbis');
            window.scrollTo(0, 0);
            Default.prototype.ajaxPopupRegister(lsUrl);
            return false;
        });
    }, // _manageRegisterPopupOpening

    _manageRegisterPopupSubmitting: function(psLoginUrl)
    {
        $(document).on("click", "#input_RBZ_register_form ", function() {
            Default.prototype.ajaxPopupRegisterSubmit('form_RBZ_register_form', psLoginUrl);
            return false;
        });
    }, // _manageLoginPopupSubmitting

    _manageRegisterPopupClosing: function()
    {
        $(document).on("click", "#div_RBZ_registerPopup #div_RBZ_close", function() {
            $("#div_RBZ_registerPopup").detach();
            $("#div_RBZ_registerShadow").hide();
            return false;
        });
    }, // _manageRegisterPopupClosing

    /**
     * Appel du popup de login en ajax
     */
    ajaxPopupLogin: function(psUrl)
    {
        // ==== Chargement de la popup ====
        // ---- Définition de la popup ----
        $.get(psUrl, function(psAjax) {
            if (psAjax == 'refresh') {
                location.reload();
            }
            $("#div_RBZ_loginPopup").remove();
            // ---- Redéfinition de la taille du masque ----
            $("#div_RBZ_loginShadow").css({ width : $(document).width(), height : $(document).height() });
            $("#div_RBZ_loginShadow").show();
            $("body").append(psAjax);

            // ---- Centrage ----
            var liWidth = $("#div_RBZ_loginPopup").width(), liDocumentWidth = $(window).width(), liLeft = 0;
            if (liWidth < liDocumentWidth) liLeft = Math.round((liDocumentWidth - liWidth) / 2);
            $("#div_RBZ_loginPopup").css('left', liLeft);
        });
    }, // ajaxPopupLogin

    /**
     * Validation du formulaire dans le popup de login
     */
    ajaxPopupLoginSubmit: function(psFormName, psUrl)
    {
        var loForm = $("#" + psFormName);
        var loData = new FormData(loForm[0]);
        $.ajax({
            url: loForm.attr('action'),
            type: 'POST',
            data: loData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(psReturn) {
                if ('OK' == psReturn) {
                    $("#div_RBZ_loginPopup").remove();
                    $("#div_RBZ_loginShadow").hide();
                    if (psUrl != undefined) {
                        window.location = psUrl;
                    }
                } else {
                    $("#div_RBZ_error").text('');
                    $("#div_RBZ_error").append(psReturn);
                }
            }

        });
    }, // ajaxPopupLoginSubmit

    /**
     * Appel du popup de register en ajax
     */
    ajaxPopupRegister: function(psUrl)
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
        });
    }, // ajaxPopupRegister

    /**
     * Validation du formulaire dans le popup de register
     */
    ajaxPopupRegisterSubmit: function(psFormName, psUrl)
    {
        var loForm = $("#" + psFormName);
        var loData = new FormData(loForm[0]);
        $.ajax({
            url: loForm.attr('action'),
            type: 'POST',
            data: loData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(psReturn) {
                if ('OK' == psReturn) {
                    $("#div_RBZ_registerPopup").remove();
                    $("#div_RBZ_registerShadow").hide();
                    if (psUrl != undefined) {
                        window.location = psUrl;
                    }
                } else {
                    $("#div_RBZ_error").text('');
                    $("#div_RBZ_error").append(psReturn);
                }
            }
        });
    }, // ajaxPopupLoginSubmit

    /**
     * Token de fin
     */
    _endPrototype : null
}; // Default.prototype

//==== Définition de l'objet Default goDefault ====
var goDefault = new Default();
