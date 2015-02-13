/**
 * Classe de gestion des popups
 *
 * <pre>
 * Victor 09/02/15 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package Popup
 */
function Popup()
{
    // ==== Constructeur ====
} // Popup
Popup.prototype = {
    /**
     * Traitements lancés en fin de chargement de la page
     */
    ready : function()
    {
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
     * Token de fin
     */
    _endPrototype : null
}; // Popup.prototype

//==== Définition de l'objet Popup goPopup ====
var goPopup = new Popup();
