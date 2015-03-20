/**
 * Classe de gestion des popups
 *
 * <pre>
 * Elias 09/02/15 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package Popup
 */
function Popup()
{
    // ==== Constructeur ====
    // ---- Affectation du fond noir ou blanc ----
    Popup.prototype._divShadow = '#div_RBZ_commonShadow';
} // Popup
Popup.prototype = {
    /**
     * Traitements lancés en fin de chargement de la page
     */
    ready : function(psDivShadow)
    {
        // ==== Popup Contact ====
        // ---- Affectation du fond selon la page ----
        if(psDivShadow !== undefined){
            Popup.prototype._divShadow = psDivShadow;
        }

        // Ouverture du popup de contact
        Popup.prototype._manageContactPopupOpening();
        // Soumission du popup de contact
        Popup.prototype._manageContactPopupSubmitting();
        // Fermeture du popup de contact
        Popup.prototype._manageContactPopupClosing();
        
        // ==== Popup Commune ====
        // fermeture popup
        $(document).on("click", "#a_RBZ_commonPopupClose, button#RBZ_closePopupButton", function() {
            if ($(this).hasClass('RBZ_hide_popup')) {
                Popup.prototype.popupClose();
                return false;
            } else {
                Popup.prototype.ajaxPopupClose();
            }
        });
        
        // ==== Appel ajax popup ====
        $("a.RBZ_ajaxPopup").click(function() {
            var lsUrl = $(this).attr('hrefbis');
            Popup.prototype.ajaxPopup(lsUrl);
            return false;
        });
    }, // ready

    /**
     * Appel d'une popup en ajax
     */
    ajaxPopup: function(psUrl, poClosure)
    {
        // ==== Définition de la popup ====
        $.get(psUrl, function(psAjax) {
            if (psAjax == 'refresh') {
                location.reload();
            }
            $("#div_RBZ_commonPopup").remove();
            // ==== Redéfinition de la taille du masque ====
            $("#div_RBZ_commonShadow").css({ width : $(document).width(), height : $(document).height() });
            $("#div_RBZ_commonShadow").show();
            $("#section_RBZ_main").append(psAjax);

            // ==== Centrage ====
            var liWidth = $("#div_RBZ_commonPopup").width(), liDocumentWidth = $(window).width(), liLeft = 0;
            if (liWidth < liDocumentWidth) liLeft = Math.round((liDocumentWidth - liWidth) / 2);
            $("#div_RBZ_commonPopup").css('left', liLeft);
            $("#div_RBZ_commonPopup").show();

            // ==== Appel de la closure de fin de chargement ====
            if (poClosure != undefined) {
                poClosure();
            }
        });
    }, // ajaxPopup

    /**
    * Fermeture de la popup
    */
    ajaxPopupClose: function()
    {
        $("#div_RBZ_commonPopup").remove();
        $("#div_RBZ_commonShadow").hide();
        return false;
    }, // ajaxPopupClose

    /**
     * Fermeture de la popup
     */
    popupClose: function()
    {
        $("#div_RBZ_commonPopup").hide();
        $("#div_RBZ_commonShadow").hide();
        return false;
    }, // ajaxPopupClose

    /**
     * Popup de confirmation
     *
     * @param msg
     * @param successClosure
     * @param errorClosure
     */
    confirm: function(msg, successClosure, errorClosure)
    {
        // ==== Dimensionnement du shadow ====
        $("#div_RBZ_commonShadow").css({ width : $(document).width(), height : $(document).height() });

        // ==== Centrage ====
        var liWidth = $("#div_RBZ_commonPopup").width();
        var liDocumentWidth = $(window).width();
        var liLeft = 0;
        if (liWidth < liDocumentWidth) {
            liLeft = Math.round((liDocumentWidth - liWidth) / 2);
        }

        $("#div_RBZ_commonPopup").css('left', liLeft);
        $('#div_RBZ_commonShadow').show();

        $('#div_RBZ_commonPopup #div_RBZ_commonPopupContent div.RBZ_confirm_popup_content').html(msg);
        $('#div_RBZ_commonPopup').show();

        $('#div_RBZ_commonPopup button.RBZ_validate').unbind().click(function() {
            $('#div_RBZ_commonPopup').hide();
            $('#div_RBZ_commonShadow').hide();
            successClosure();
        });

        $('#div_RBZ_commonPopup button.RBZ_cancel').unbind().click(function() {
            $('#div_RBZ_commonPopup').hide();
            $('#div_RBZ_commonShadow').hide();
            if (errorClosure != undefined) {
                errorClosure();
            }
        });
    }, // confirm
    
    
    /**
    * ========================= Popup Contact =========================
    */
    
    _manageContactPopupOpening: function()
    {
        $(document).on("click", "#li_RBZ_contactPopupCall a:first", function() {
            var lsUrl = $(this).attr('hrefbis');
            Popup.prototype.ajaxPopupContact(lsUrl);
            scroll(0, 0);
            return false;
        });
    }, // _manageContactPopupOpening

    _manageContactPopupSubmitting: function()
    {
        $(document).on("click", "#input_RBZ_contact_form", function() {
            Popup.prototype.ajaxPopupContactSubmit('form_RBZ_contact_form');
            return false;
        });
    }, // _manageLoginPopupSubmitting

    _manageContactPopupClosing: function()
    {
        $(document).on("click", "#div_RBZ_contactPopup #div_RBZ_close", function() {
            $("#div_RBZ_contactPopup").detach();
            $(Popup.prototype._divShadow).hide();
            return false;
        });
    }, // _manageContactPopupClosing

    /**
     * Appel du popup de contact en ajax
     */
    ajaxPopupContact: function(psUrl)
    {
        // ==== Définition de la popup ====
        $.get(psUrl, function(psAjax) {
            if (psAjax == 'refresh') {
                location.reload();
            }
            $("#div_RBZ_contactPopup").remove();
            // ==== Redéfinition de la taille du masque ====
            $(Popup.prototype._divShadow).css({ width : $(document).width(), height : $(document).height() });
            $(Popup.prototype._divShadow).show();
            $("body").append(psAjax);

            // ==== Centrage ====
            var liWidth = $("#div_RBZ_contactPopup").width(), liDocumentWidth = $(window).width(), liLeft = 0;
            if (liWidth < liDocumentWidth) liLeft = Math.round((liDocumentWidth - liWidth) / 2);
            $("#div_RBZ_contactPopup").css('left', liLeft);
        });
    }, // ajaxPopupContact

    /**
     * Validation du formulaire dans le popup de contact
     */
    ajaxPopupContactSubmit: function(psFormName)
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
                    $("#div_RBZ_contactPopup").remove();
                    $(Popup.prototype._divShadow).hide();
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
}; // Popup.prototype

//==== Définition de l'objet Popup goPopup ====
var goPopup = new Popup();
