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
        $(document).on("click", "#a_RBZ_commonPopupClose, button#RBZ_closePopupButton", this.ajaxPopupClose);
        
        //appel ajax popup
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

            // ==== Appel de la closure de fin de chargement ====
            if (poClosure != undefined) {
                poClosure();
            }
        });
    }, // ajaxPopup
    
    /**
     * Validation d'un formulaire dans une popup
     */
    ajaxPopSubmit: function(psFormName, pbRefresh, poClosure)
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
                    $("#div_RBZ_commonPopup").remove();
                    $("#div_RBZ_commonShadow").hide();
                    if (pbRefresh == true) {
                        location.reload();
                    } else if (pbRefresh != undefined) {
                        window.location = pbRefresh;
                    }

                    // ==== Appel de la closure de fin de chargement ====
                    if (poClosure != undefined) {
                        poClosure();
                    }
                } else {
                    $("#div_RBZ_commonPopup").remove();
                    $("#section_RBZ_main").append(psReturn);

                    // ==== Centrage ====
                    var liWidth = $("#div_RBZ_commonPopup").width(), liDocumentWidth = $(document).width(), liLeft = 0;
                    if (liWidth < liDocumentWidth) liLeft = Math.round((liDocumentWidth - liWidth) / 2);
                        $("#div_RBZ_commonPopup").css('left', liLeft);


                    // ==== Appel de la closure de fin de chargement ====
                    if (poClosure != undefined) {
                        poClosure();
                    }
                }
                $(document).scrollTop(0);
            }
        });
    }, // ajaxPopSubmit

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
    * ========================= Popup Contact =========================
    */
    
    _manageContactPopupOpening: function()
    {
        $(document).on("click", "#li_RBZ_contactPopupCall a", function() {
            var lsUrl = $(this).attr('hrefbis');
            Popup.prototype.ajaxPopupContact(lsUrl);
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
    ajaxPopupContactSubmit: function(psFormName, psUrl)
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
                    if (psUrl != undefined) {
                        window.location = psUrl;
                    } else {
                        location.reload();
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
}; // Popup.prototype

//==== Définition de l'objet Popup goPopup ====
var goPopup = new Popup();
