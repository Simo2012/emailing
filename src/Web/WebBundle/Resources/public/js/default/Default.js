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
    ready : function()
    {
        // Ouverture des popup de login
        $(document).on("click", "span.RBZ_default_login a", function() {
            var lsUrl = $(this).attr('hrefbis');
            Default.prototype.ajaxPopupLogin(lsUrl);
            return false;
        });
        // Ouverture des popup de register
        $(document).on("click", "span.RBZ_default_register a", function() {
            var lsUrl = $(this).attr('hrefbis');
            Default.prototype.ajaxPopupRegister(lsUrl);
            return false;
        });
    }, // ready

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

//==== Définition de l'objet Default goAaf ====
var goDefault = new Default();
