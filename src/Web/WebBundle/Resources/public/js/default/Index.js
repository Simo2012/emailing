/**
 * Classe de Default
 *
 * <pre>
 * Hrishikesh 11/02/15 Création
 * </pre>
 * @author Hrishikesh
 * @version 1.0
 * @package Default
 */
function Index()
{
    // ==== Constructeur ====
} // Index
Index.prototype = {
    /**
     * Traitements lancés en fin de chargement de la page
     */
    ready : function()
    {
    }, // ready

    /**
     * Head slider
     */
    rubSlider: function()
    {
        $(document).ready(function() {
          $("#div_RBZ_sliderContain").owlCarousel({
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
              autoplay: true
          });
        });
    }, // rubSlider

    /**
     * Token de fin
     */
    _endPrototype : null
}; // Index.prototype

//==== Définition de l'objet Index goIndex ====
var goIndex = new Index();
