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
} // Global
Hiw.prototype = {
    /**
     * Traitements lancés en fin de chargement de la page
     */
    ready: function()
    {
        $(document).on('click', '.li_RBZ_hiwMenu', function() {
            var numberPage = $(this).text();
            // ---- Cacher toutes les pages ----
            $(".div_RBZ_hiw_page").hide();
            // ---- Affiche la bonne page ----
            if (numberPage == '1' || numberPage == '3' || numberPage == '6') {
                $("body").css("background-color", "#ADE1EC");
                $("#div_RBZ_hiw_slider").css("color", "#fff");
                $("#div_RBZ_hiw_slider h3").css("color", "#fff");
                $(".span_RBZ_hiwCircle").css("background-color", "#fff");
                $("#header_RBZ_header #div_RBZ_banner").css("background-image", "url(/bundles/webweb/images/global/logo.png)");
                $("#header_RBZ_header #div_RBZ_banner #div_RBZ_headerMenu a").css("color", "#fff");
                $("#header_RBZ_header #div_RBZ_banner #div_RBZ_headerMenu div.RBZ_menuVBar").css("border-left", "1px solid #fff");
                $("#header_RBZ_header #div_RBZ_banner #div_RBZ_headerMenu div.RBZ_menuVBar").css("border-right", "1px solid #fff");
            } else {
                $("body").css("background-color", "#F1F1F1");
                // ---- menu du slider ----
                $("#div_RBZ_hiw_slider").css("color", "gray");
                $("#div_RBZ_hiw_slider h3").css("color", "gray");
                $(".span_RBZ_hiwCircle").css("background-color", "gray");
                $("#header_RBZ_header #div_RBZ_banner").css("background-image", "url(/bundles/webweb/images/global/redLogo.png)");
                $("#header_RBZ_header #div_RBZ_banner #div_RBZ_headerMenu a").css("color", "#353537");
                $("#header_RBZ_header #div_RBZ_banner #div_RBZ_headerMenu div.RBZ_menuVBar").css("border-left", "1px solid #353537");
                $("#header_RBZ_header #div_RBZ_banner #div_RBZ_headerMenu div.RBZ_menuVBar").css("border-right", "1px solid #353537");
            }
            $("#div_RBZ_hiw_p" + numberPage).show();

            return false;
        });
    }, // ready

    /**
     * Token de fin
     */
    _endPrototype: null
}; // Global.prototype

//==== Définition de l'objet Global goGlobal ====
var goHiw = new Hiw();
goHiw.ready();