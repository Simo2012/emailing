/**
 * Classe de gestion globale
 *
 * <pre>
 * Victor 08/02/15 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package default
 */
function Hiw()
{
    // ==== Constructeur ====
    // ---- Permet de savoir si l'user est connecté ----
    Popup.prototype._pbIsConnected = false;
} // Global
Hiw.prototype = {
    /**
     * Traitements lancés en fin de chargement de la page
     */
    ready: function(pbIsConnected)
    {
        // ---- Permet de savoir si l'user est connecté ----
        if (pbIsConnected !== undefined) {
            Popup.prototype._pbIsConnected = pbIsConnected;
        }
        $(document).on('click', '.RBZ_hiwMenu', function() {
            var numberPage = $(this).text();
            Hiw.prototype._manageChangePage($(this), numberPage);
            return false;
        });

        // ==== Clique sur le bouton slide haut ====
        $(document).on('click', '#span_RBZ_hiwSliderTop', function() {
            // ---- Trouver la li active ----
            var liNumberPage = $('li[active]').attr('active');
            // ---- Récupérer la li précédente ----
            var loPreviousLi = $('li[active]').prev();
            if (liNumberPage > 1 && liNumberPage != undefined) {
                var liPage = liNumberPage - 1;
                Hiw.prototype._manageChangePage(loPreviousLi, liPage);
            }
            return false;
        });
        // ==== Clique sur le bouton slide bas ====
        $(document).on('click', '#span_RBZ_hiwSliderDown', function() {
            // ---- Trouver la li active ----
            var liNumberPage = $('li[active]').attr('active');
            // ---- Récupérer la li précédente ----
            var loPreviousLi = $('li[active]').next();
            if (liNumberPage == undefined) {
                liNumberPage = 1;
                loPreviousLi = $('.RBZ_hiwMenu').first();
            }
            if (liNumberPage < 6 && liNumberPage != undefined) {
                var liPage = parseInt(liNumberPage) + 1;
                Hiw.prototype._manageChangePage(loPreviousLi, liPage);
            }
            return false;
        });

    }, // ready

    /**
     * Gestion du changement de page (couleurs , effets etc)
     */
    _manageChangePage: function(poElement, piNumberPage)
    {
        // ---- Cacher toutes les pages ----
        $(".RBZ_hiw_page").hide();
        // ---- Affiche la bonne page ----
        if (piNumberPage == '1' || piNumberPage == '3' || piNumberPage == '6') {
            $("body").css("background-color", "#ADE1EC");
            $("#div_RBZ_hiw_slider").css("color", "#fff");
            $("#div_RBZ_hiw_slider h3").css("color", "#fff");
            $("RBZ_hiwCircle").css("background-color", "#fff");
            $("#header_RBZ_header #div_RBZ_banner").css("background-image", "url(/bundles/webweb/images/global/logo.png)");
            $("#header_RBZ_header #div_RBZ_banner #div_RBZ_headerMenu a").css("color", "#fff");
            $("#header_RBZ_header #div_RBZ_banner #div_RBZ_headerMenu div.RBZ_menuVBar").css("border-left", "1px solid #fff");
            $("#header_RBZ_header #div_RBZ_banner #div_RBZ_headerMenu div.RBZ_menuVBar").css("border-right", "1px solid #fff");
        } else {
            $("body").css("background-color", "#F1F1F1");
            // ---- menu du slider ----
            $("#div_RBZ_hiw_slider").css("color", "gray");
            $("#div_RBZ_hiw_slider h3").css("color", "gray");
            $("RBZ_hiwCircle").css("background-color", "gray");
            // ---- Si l'utilisateur est en mode logout ----
            if (!Popup.prototype._pbIsConnected) {
                $("#header_RBZ_header #div_RBZ_banner").css("background-image", "url(/bundles/webweb/images/global/redLogo.png)");
                $("#header_RBZ_header #div_RBZ_banner #div_RBZ_headerMenu a").css("color", "#353537");
                $("#header_RBZ_header #div_RBZ_banner #div_RBZ_headerMenu div.RBZ_menuVBar").css("border-left", "1px solid #353537");
                $("#header_RBZ_header #div_RBZ_banner #div_RBZ_headerMenu div.RBZ_menuVBar").css("border-right", "1px solid #353537");
            }
        }
        $("#div_RBZ_hiw_p" + piNumberPage).show();

        // ==== Réinitialisation effet active ====
        $('.RBZ_hiwMenu').css('opacity', '0.4');
        // ---- Effet active ----
        $(poElement).css('opacity', '1');
        // ==== Réinitialisation attribut active ====
        $('.RBZ_hiwMenu').removeAttr('active');
        $(poElement).attr('active', piNumberPage);

    }, // _manageChangePage

    /**
     * Token de fin
     */
    _endPrototype: null
}; // Global.prototype

//==== Définition de l'objet Global goGlobal ====
var goHiw = new Hiw();