/**
 * Classe de gestion Offer
 *
 * <pre>
 * Mohammed 09/02/15 Création
 * </pre>
 * @author Mohammed
 * @version 1.0
 * @package Offer
 */
function Offer()
{   
    // ==== Constructeur ====
} // Offer

Offer.prototype = {
    /**
     * Traitements lancés en fin de chargement de la page
     */
    ready : function()
    {
        //alert($(this));
        $(document).on('mouseenter mouseleave', '.RBZ_offer_content', function(event) {
            $(this).toggleClass('RBZ_expanded').animate();
            $('.RBZ_offer_description_hide').toggleClass('RBZ_hide').animate();
            //$('RBZ_actions').siblings('a').find('RBZ_hide').hide();
            if($('.RBZ_offer_input').find('a').hasClass('RBZ_hide')==false)
            {                        
                $('.RBZ_actions').siblings('a').toggleClass('RBZ_hide');
                $('.RBZ_actions').parent().toggleClass('RBZ_expanded');
            }
                //alert();
           
        });
        $(document).on('click', '.RBZ_actions', function(event) {
            //$('.RBZ_offer_description_hide').toggleClass('RBZ_hide').animate();
            $(this).siblings('a').toggleClass('RBZ_hide');
            $(this).parent().toggleClass('RBZ_expanded');
        });
        
    }, // ready

    /**
     * Token de fin
     */
    _endPrototype : null
}; // Offer.prototype

//==== Définition de l'objet Offer ====
var goOffer = new Offer();
goOffer.ready();