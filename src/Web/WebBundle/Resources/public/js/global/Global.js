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
        $(document).on('mouseenter mouseleave', '#ul_RBZ_menuProfile > li', function(event) {
            $(this).children('ul').toggle();
            return false;
        });

        $(document).on('click', '#a_RBZ_linkMenuMobile', function(event) {
            $('#div_RBZ_menuMobile').toggleClass('RBZ_hide');
            return false;
        });
        // ==== RUB Slider ====
        this.rubSlider();
    }, // ready

    /**
     * Head slider
     */
    rubSlider: function()
    {
        $(document).ready(function() {
          $("#div_RUB_sliderContain").owlCarousel({
//            items: 1,
//            autoPlay: 3000,
//            navigation: true,
//            navigationText: ["<", ">"],
//            itemsTablet: [767,1],
//            itemsTabletSmall: [767,1],
//            itemsMobile: [480,1],
//            navigation : true,
//            singleItem : true,
//            transitionStyle : "fade"
              loop:true,
              margin:10,
              nav:true,
              items: 1,
              navText: false,
              dots: true,
              animateOut: 'fadeOut',
              animatein: 'fadein',
              // autoplay: true
          });
        });
    }, // rubSlider

    /**
     * Token de fin
     */
    _endPrototype : null
}; // Global.prototype

//==== Définition de l'objet Global goAaf ====
var goGlobal = new Global();
